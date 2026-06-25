<?php

namespace App\Http\Controllers\zcrat;
use App\Http\Controllers\controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

class UsuariosController extends Controller
{

    public function view(){
        $elementostotales = User::count();
        return view('zcrat.usuariosadmin',compact('elementostotales'));
    }
    public function Alls(Request $request){
        $page=$request->input('currentPage',1);
        $itemsPerPage=$request->input('itemsPerPage',10);
        $elements = User::with('Taller')->paginate($itemsPerPage,['*'],'page',$page);
        $totalelements=$elements->total();
        $elements= $elements->map(function ($user) {
            return [
                'id'   => $user->id,
                'usuario' => strtoupper($user->email),
                'name' => strtoupper($user->name),  
                'taller_id' =>$user->taller_id,  
                'taller' =>$user->Taller->nombre,  
                'fecha' => $user->created_at,  
            ];
        });

        return response()->json(compact('elements','totalelements'));
    }
    public function UpdateTaller(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'id' => 'required|exists:users,id',
            'taller' => 'required|exists:talleres,id',
        ], [
            'id.required' => 'El ID del Usuario es obligatorio.',
            'id.exists' => 'El ID del Usuario no existe.',
            'taller.required' => 'El Taller es obligatorio.',
            'taller.exists' => 'El Taller no es válido.',
        ]);
        try {
            $tallerid=\Auth::user()->taller_id ?? 3;
            $deta=User::where('id',$request->input('id'))->first();
            $deta->taller_id=$request->input('taller');
            $deta->save();
            
            return response()->json(['success' => 'taller actualizada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar el taller.', 'details' => $e->getMessage()], 500);
        }
    }
    public function PermisosRoles(Request $request){
        $user=$request->input('id',null);
        $roles=[];
        $permisos=[];
        $permisosdirectos=[];
        $permisosheredados=[];
        $rolesusuario=[];
        $datos=[
            'name'=>'',
            'email'=>''
        ];
        $user=User::find($user);
        if(!empty($user)){
            $permisos = Permission::orderBy('id', 'desc')->pluck('name')->toarray();
            $roles = Role::all()->pluck('slug')->toarray();
            $isAdmin = $user->roles->contains('slug', 'admin');
            $rolesusuario=$user->roles->pluck('slug')->toarray();
            $permisosdirectos=$isAdmin?[]:$user->permissions->pluck('name')->toarray();
            $permisosheredados =$isAdmin?$permisos:$user->roles->load('permissions')
            ->pluck('permissions')
            ->flatten()
            ->unique('name')->pluck('name')->toarray();
           $datos = [
                'name'  => $user->name,
                'email' => $user->email,
            ];

        }
        return response()->json(compact('roles','permisos','permisosdirectos','permisosheredados','rolesusuario','datos'));
    }
    public function ToggleRol(Request $request){
        $user=$request->input('user',null);
        $rol=$request->input('rol',null);
        try{

            $idsvalidos = [1, 170];
            if (!in_array($request->user()->id, $idsvalidos)) {
                throw new \Exception('Datos Incompletos');
            } 
            if(!$user || !$rol){
                throw new \Exception('Datos Incompletos');
            }
            $user=User::find($user);
            if(!$user){
                throw new \Exception('Usuario No Disponible');
            }
            $rol = Role::where('slug', $rol)->first();

            if ($user->roles->contains('slug', $rol->slug)) {
                $user->roles()->detach($rol->id);
               
            } else {
                $user->roles()->attach($rol->id);
                
            }

            return response()->json(['message'=>'exito']);
        }catch(\Throwable $th){
            return response()->json(['message'=>$th->getMessage()],500);
        }
    }
    public function TogglePermiso(Request $request){
        $user=$request->input('user',null);
        $permiso=$request->input('permiso',null);
        try{

            $idsvalidos = [1, 170];
            if (!in_array($request->user()->id, $idsvalidos)) {
                throw new \Exception('Datos Incompletos');
            } 
            if(!$user || !$permiso){
                throw new \Exception('Datos Incompletos');
            }
            $user=User::find($user);
            if(!$user){
                throw new \Exception('Usuario No Disponible');
            }
            $permiso = Permission::where('name', $permiso)->first();

            if (!$permiso) {
                throw new \Exception( "El permiso {$permiso} no existe.");
            }
            $heredado = $user->roles->load('permissions')
                ->pluck('permissions')
                ->flatten()
                ->contains('name', $permiso->name);

            if ($heredado || $user->roles->contains('slug','admin')) {
                throw new \Exception( "El permiso {$permiso->name} está heredado por rol, no se puede modificar directamente.");
            }
            if ($user->permissions->contains('name', $permiso->name)) {
                $user->permissions()->detach($permiso->id);
            } else {
                $user->permissions()->attach($permiso->id);
            }
            return response()->json(['message'=>'exito']);
        }catch(\Throwable $th){
            return response()->json(['message'=>$th->getMessage()],500);
        }
    }
    public function CreateOrUpdate(Request $request){
        $name=$request->name;
        $email=$request->email;
        $id=$request->id;
        $password=$request->password;
        try{
            if(empty($id)){
                if(empty($password)||empty($email)||empty($name)){
                    throw new \Exception('Datos Incompletos');
                }
                if (User::where('email', $email)->exists()) {
                    throw new \Exception('El username ya está en uso');
                }

                $usuario=new User;
                $usuario->name=$request->get('name');
                $usuario->email=$request->get('email');
                $usuario->password=bcrypt($request->get('password'));
                $usuario->sucursal_id=$request->get('sucursal',10);
                $usuario->save();
            }else{
                $usuario=User::find($id);
                if(!$usuario){
                    throw new \Exception('Usuario No Disponible');
                }
                if(empty($email)||empty($name)){
                    throw new \Exception('Datos Incompletos');
                }
                if (User::where('email', $email)->where('id', '!=', $id)->exists()) {
                    throw new \Exception('El username ya está en uso por otro usuario');
                }

                $usuario->name=$request->get('name');
                $usuario->email=$request->get('email');
                if(!empty($password)){
                    $usuario->password=bcrypt($request->get('password'));
                }
                $usuario->save();
            }
           return response()->json(['message'=>'exito','id'=>$usuario->id]);
        }catch(\Throwable $th){
            return response()->json(['message'=>$th->getMessage()],500);
        }
    }
}
