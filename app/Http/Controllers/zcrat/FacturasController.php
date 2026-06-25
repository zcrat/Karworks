<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\DetallesPrefacturas;
use App\Models\Prefactura;
use App\Models\PresupuestoCarrito;
use App\Models\Presupuesto;
use App\Models\DetalleFactura;
use App\Models\FacturasEmisor;
use App\Models\DetallesGenerales;
use App\Models\CategoriasSat;
use App\Models\Empresa;
use App\Models\Contratos;
use Carbon\Carbon;
use App\Classes\Facturar;
use App\Classes\Certificados;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\LOG;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturasController extends Controller
{
    public function FacturaUnitaria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $logotipo = '';
        $emisorid = 0;
        

        $emisor = $request->emisor; 
        $presupuesto = $request->presupuesto;

        if($emisor == 'Emisor3'){
            $logotipo = 'logo_cfb_fact.png';
            $emisorid = 1;
        }
        else if($emisor == 'Emisor4'){
            $logotipo = 'logo_akumas_fct.png';
            $emisorid = 2;
        } 
        else if($emisor == 'Emisor5'){
            $logotipo = 'logo_kmg.jpeg';
            $emisorid = 3;
        }else if($emisor == 'Emisor6'){
            $logotipo = 'karworks_logotipo_fact.jpeg';
            $emisorid = 4;
        }

        $actualizarprefactura = false;
        $idprefacturaactualizar = 0;
        log::info('solicitud recibida');
        try{
            DB::beginTransaction();
            $idsprefacturas=DetallesPrefacturas::where('presupuesto_id',$presupuesto)->pluck('prefactura_id')->toArray();
            $presupuestosordenados = [$presupuesto];
            sort($presupuestosordenados);
            
            if(!empty($idsprefacturas)){
                $idsprefacturas = array_unique($idsprefacturas);
                if(count($idsprefacturas) == 1){
                    $prefactura=$idsprefacturas[0];
                    $idspresupuestosprefacturas=DetallesPrefacturas::where('prefactura_id',$prefactura)->pluck('presupuesto_id')->toArray();
                    $idspresupuestosprefacturas = array_unique($idspresupuestosprefacturas);
                    sort($idspresupuestosprefacturas); 
                    
                    if($presupuestosordenados == $idspresupuestosprefacturas){
                        $actualizarprefactura = true;
                     $idprefacturaactualizar = $prefactura;
                    }else{
                     if(empty(array_diff($idspresupuestosprefacturas, $presupuestosordenados))){
                        DetallesPrefacturas::where('prefactura_id',$prefactura)->delete();
                        Prefactura::where('id',$prefactura)->delete();
                    }else{
                         DetallesPrefacturas::where('prefactura_id',$prefactura)->WhereIn('presupuesto_id',$presupuestosordenados)->delete();
                        }
                    }
                }else{
                 foreach($idsprefacturas as $prefactura){
                     $idspresupuestosordenadosprefacturas=DetallesPrefacturas::where('prefactura_id',$prefactura)->pluck('presupuesto_id')->toArray();
                     $idspresupuestosordenadosprefacturas = array_unique($idspresupuestosordenadosprefacturas);
                     sort($idspresupuestosordenadosprefacturas);
                     if($presupuestosordenados == $idspresupuestosordenadosprefacturas && !$actualizarprefactura){
                            $actualizarprefactura = true;
                            $idprefacturaactualizar = $prefactura;
                        }else{
                            if(empty(array_diff($idspresupuestosordenadosprefacturas, $presupuestosordenados))){
                                DetallesPrefacturas::where('prefactura_id',$prefactura)->delete();
                            Prefactura::where('id',$prefactura)->delete();
                        }else{
                            DetallesPrefacturas::where('prefactura_id',$prefactura)->WhereIn('presupuesto_id',$presupuestosordenados)->delete();
                        }
                    }
                    
                }
                }
            }
            $factura = new Factura();
            $factura->empresa_id = $request->empresa;
            $factura->emisor_id = $emisorid;
            $factura->idusuario = Auth::user()->id;
            $factura->xml = '';
            $factura->pdf = '';
            $factura->estado = 'Registrado';
            $factura->movimiento = 'Facturacion';
            $factura->n_movimiento = '0';
            $factura->save();               
            
            $conceptos = PresupuestoCarrito::where("Presupuesto_id",$request->presupuesto)->orderBy('id', 'desc')->get();
            $DatosPresupuesto = Presupuesto::with(['detallesGenerales.Vehiculo','detallesGenerales.contrato'])->find($presupuesto);
            
            foreach($conceptos as $concepto)
            {
                $detalle = new DetalleFactura();
                $detalle->factura_id = $factura->id;
                $detalle->idarticulo = $concepto->Concepto_id;
                $detalle->cantidad = $concepto->Cantidad;
                $detalle->precio = $concepto->Venta;          
                $detalle->save();
            }  
            
            $dato = [];
            $dato['tipo_comprobante'] = $request->tipo_comprobante;
            $dato['uso_cfdi'] = $request->uso_cfdi;
            $dato['moneda'] = $request->moneda;
            $dato['fpago'] = $request->fpago;
            $dato['mpago'] = $request->mpago;
            
            $texto1= (($request->has('numauto') && $request->numauto !== null && $request->numauto !== '' && $request->numauto !== 'null' )? "AUTORIZACION: ".$request->numauto:"CONTRATO: ".$DatosPresupuesto->detallesGenerales->contrato->numero)." \n ECONOMICO: ".$DatosPresupuesto->detallesGenerales->Vehiculo->no_economico." \n  PLACAS: ".$DatosPresupuesto->detallesGenerales->Vehiculo->placas." \n KILOMETRAJE: ".$DatosPresupuesto->detallesGenerales->Kilometraje_entrada." \n FOLIO: ".$DatosPresupuesto->Folio;
            
            $factura_emisor = FacturasEmisor::select(
                'id'
                ,'n_certificado'
                ,'archivo_cer'
                ,'archivo_key'
                ,'clave_key'
                ,'rfc_emisor as rfc'
                ,'nombre_emisor as nombre'
                ,'regimen_emisor as regimen'
                ,'codigo_emisor as codigo'
                ,'serie_factura as serie'
                ,'folio_factura as folio')
            ->findorfail($emisorid);
                
                
            $detalles_todo = DetalleFactura::join('conceptosnuevos','detalle_facturas.idarticulo','=','conceptosnuevos.id')
                ->join('categorias_sat','conceptosnuevos.Categoria_sat_id','=','categorias_sat.id')
                ->join('unidades','conceptosnuevos.unidades_sat_id','=','unidades.id')
                ->select('conceptosnuevos.id','categorias_sat.codigo_sat','unidades.clave as unidad_sat','unidades.nombre as unidad',
                'conceptosnuevos.descripcion','detalle_facturas.cantidad','detalle_facturas.precio')
                ->where('detalle_facturas.factura_id','=',$factura->id)->orderBy('detalle_facturas.id', 'asc')
            ->paginate(100);
                
            $empresa = Empresa::select('id','nombre','rfc','logo','cp','regimen')->where('id', '=', $request->empresa)->first();
                
            $numero_certificado = $factura_emisor->n_certificado;
            $archivo_cer = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_cer;
            $archivo_key = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_key;
                
            log::info('solicitud preparando');
            // generar y sellar un XML con los CSD de pruebas
            $cfdi = new Facturar();
            $docxml = $cfdi->crearXML($empresa, $factura_emisor, $detalles_todo, $dato);          
            log::info('solicitud XML preparaDo');
            $keypem = new Certificados();
            $keypem->generaKeyPem($archivo_key,$factura_emisor->clave_key);
            log::info('solicitu KEY');
            $selladoxml = $cfdi->satxmlsv40_sella($docxml, $numero_certificado, $archivo_cer.'.pem',$archivo_key.'.pem');
            log::info('solicitu PREPARANDO');
            file_put_contents(public_path().'/facturas/factura.xml',$selladoxml);
            $nombrearchivoxml = public_path().'/facturas/factura.xml';
            log::info('solicitu SELLADO');
            log::info($selladoxml);
            // throw new \Exception('hay que ver el xml');
            $username = 'facturacion@aurumfixmotors.com';
            $password = 'Akumas2019##';
            
            $invoice_path = $nombrearchivoxml;
            $xml_file = fopen($invoice_path, "rb");
            $xml_content = fread($xml_file, filesize($invoice_path));
            fclose($xml_file);
            
            log::info('solicitud sear enviada');
            // DB::rollBack();
            // return response()->json(['message'=>'prueba exitosa'],500);
            log::info($xml_content);
            $client = new \SoapClient('https://facturacion.finkok.com/servicios/soap/stamp.wsdl');
            
            $params = array(
                "xml" => $xml_content,
                "username" => $username,
                "password" => $password
            );
            $response = $client->__soapCall("stamp", array($params));
            $response2 = \Response::json($response->stampResult);
            LOG::INFO($response2);
            LOG::INFO($xml_content);
            if (isset($response->stampResult->Incidencias) && isset($response->stampResult->Incidencias->Incidencia) ) {
                throw new \Exception($response->stampResult->Incidencias->Incidencia->MensajeIncidencia);
            }
           $folionvo = $factura_emisor->folio + 1;		
           $nombre = public_path().'/facturas/'.$factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.".xml";

          
         
           $file = fopen($nombre, "a");
           fwrite($file, $response->stampResult->xml);
           fclose($file);

           $pdfarch = $cfdi->generarPDF($nombre,$logotipo,$texto1);

           $napdf = $factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.'.pdf';
           $naxml = $factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.'.xml';
           
           $factAct = Factura::findOrFail($factura->id);
           $factAct->xml = $naxml;
           $factAct->pdf = $napdf;
           $factAct->save();
       
           $facteAct = FacturasEmisor::findOrFail($factura_emisor->id);
           $facteAct->folio_factura = $folionvo;
           $facteAct->save();


           $cotizacion = Presupuesto::find($presupuesto);
           $cotizacion->Factura_id = $factura->id;
           $cotizacion->Status_id = 5;
           $cotizacion->save();
            
           if($actualizarprefactura){
                Prefactura::where('id',$idprefacturaactualizar)->update([
                'factura_id' => $factura->id,
                'facturada' => 1
                ]);
            }

            DB::commit();
            return response()->json(['success' => 'Se Facturo Correctamente'], 200);
        } catch (\Exception $e){
            DB::rollBack();
            log::info($e);
            return response()->json(['message' => $e->getMessage()], 499);
        }
    }
    public function FacturaCancelar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        try{
            DB::beginTransaction();
            $factura = Factura::findorfail($request->CanFacId);

            $factura_emisor = FacturasEmisor::select(
                'id'
                ,'n_certificado'
                ,'archivo_cer'
                ,'archivo_key'
                ,'clave_key'
                ,'rfc_emisor as rfc'
                ,'nombre_emisor as nombre'
                ,'regimen_emisor as regimen'
                ,'codigo_emisor as codigo'
                ,'serie_factura as serie'
                ,'folio_factura as folio')->findorfail($factura->emisor_id);

            Log::info($factura_emisor);

            $numero_certificado = $factura_emisor->n_certificado;
            $archivo_cer = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_cer;
            $archivo_key = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_key;
            $cer_path = public_path()."/utilerias/certificados/certificado.pem"; 
            $key_path = public_path()."/utilerias/certificados/llave.enc";


            $username = 'facturacion@aurumfixmotors.com';
            $password = 'Akumas2019##';
            # Generar el certificado y llave en formato .pem
            shell_exec("openssl x509 -inform DER -outform PEM -in ".$archivo_cer." -pubkey -out ".public_path()."/utilerias/certificados/certificado.pem");
            shell_exec("openssl pkcs8 -inform DER -in ".$archivo_key." -passin pass:".$factura_emisor->clave_key." -out ".public_path()."/utilerias/certificados/llave.key.pem");
            shell_exec("openssl rsa -in ".public_path()."/utilerias/certificados/llave.key.pem -des3 -out ".public_path()."/utilerias/certificados/llave.enc -passout pass:".$password);

            $cer_file = fopen($cer_path, "r");
            $cer_content = fread($cer_file, filesize($cer_path));
            fclose($cer_file);
            
            $key_file = fopen($key_path, "r");
            $key_content = fread($key_file,filesize($key_path));
            fclose($key_file);  

            $xml = new \SimpleXMLElement (public_path().'/facturas/'.$factura->xml,null,true);

            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('c', $ns['cfdi']);
            $xml->registerXPathNamespace('t', $ns['tfd']);

           
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
                $xml->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
                foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//tfd:TimbreFiscalDigital') as $tfd){
                    $emisor2 = $Emisor['Rfc'];
                    $sello5 = $tfd['UUID'];
                    $taxpayer_id = $emisor2; # The RFC of the Emisor
                    $invoices = array($sello5); # A list of UUIDs
                }
            }
            
            $relacion =$request->CanFacFol ?? "";

            $idvoc =  $invoices[0];

            $url = "https://facturacion.finkok.com/servicios/soap/cancel.wsdl";
            $client = new \SoapClient($url, array('trace' => 1));
            
            $uuids = array("UUID" => "$idvoc", "Motivo" => $request->CanFacMot, "FolioSustitucion" => $relacion);
            $uuid_ar = array('UUID' => $uuids);
            $params = array("UUIDS"=>$uuid_ar,
                            "username" => $username,
                            "password" => $password,
                            "taxpayer_id" => "$taxpayer_id",
                            "cer" => $cer_content,
                            "key" => $key_content
                        );
            $response = $client->__soapCall("cancel", array($params));
            $response2 = \Response::json($response);
           
            log::info($response2);
            # Generación de archivo .xml con el Request de timbrado
            $file = fopen(public_path().'/facturas/'."Cancel-$idvoc.xml", "a");
            fwrite($file, $client->__getLastRequest() . "\n");
            fclose($file);

            if(isset($response->cancelResult->CodEstatus) && !isset($response->cancelResult->Folios)){ 
                throw new \Exception($response->cancelResult->CodEstatus);
            }
            $resp = $response->cancelResult->Folios->Folio->EstatusCancelacion;
            $factAct = Factura::findOrFail($factura->id);
            $factAct->acuse = "Cancel-$idvoc.xml";
            $factAct->estado = $resp;
            $factAct->save();
        
            $presupuestoscancelados=Presupuesto::where('factura_id', $factura->id)->pluck('id');
            Presupuesto::where('factura_id', $factura->id)->update(['Status_id' => 4]);
            Prefactura::where('factura_id', $factura->id)->update(['facturada' => 0]);
            $prefacturas=DetallesPrefacturas::onlyTrashed()->whereIn('presupuesto_id',$presupuestoscancelados)->pluck('prefactura_id')->toArray();
            DetallesPrefacturas::onlyTrashed()->whereIn('presupuesto_id',$presupuestoscancelados)->restore();
            if(!empty($prefacturas)){
                Prefactura::onlyTrashed()->whereIn('id',$prefacturas)->restore();
            }
            DB::commit();
           return response()->json(['mesagge' => 'Se Cancelo Correctamente'], 200);
        } catch (\Exception $e){
            DB::rollBack();
            DB::beginTransaction();
            try{
                $presupuestoscancelados=Presupuesto::where('factura_id', $factura->id)->pluck('id');
                Presupuesto::where('factura_id', $factura->id)->update(['Status_id' => 4]);
                Prefactura::where('factura_id', $factura->id)->update(['facturada' => 0]);
                $prefacturas=DetallesPrefacturas::onlyTrashed()->whereIn('presupuesto_id',$presupuestoscancelados)->pluck('prefactura_id')->toArray();
                DetallesPrefacturas::onlyTrashed()->whereIn('presupuesto_id',$presupuestoscancelados)->restore();
                if(!empty($prefacturas)){
                    Prefactura::onlyTrashed()->whereIn('id',$prefacturas)->restore();
                }
                DB::commit();
               return response()->json(['mesagge' => 'No se Pudo Cancelar Solo Se Regreso'], 200);
            }
            catch(\Exception $e){
                DB::rollBack();
                return response()->json(['message' => $e->getmessage()], 500);
            }
        }
    }
    public function PreFacturaCreate(Request $request)
    {
        $dato = [];

        if (!$request->ajax()) return redirect('/');
 
        try{
            DB::beginTransaction();

            $factura = new Prefactura();
            $factura->empresa_id = $request->empresa_id;
            $factura->user_id = \Auth::user()->id;
            $factura->fpago = $request->fpago;
            $factura->moneda = $request->moneda;
            $factura->mpago = $request->mpago;
            $factura->tipo_comprobante = $request->tipo_comprobante;
            $factura->tipo_impuesto_local = $request->tipo_impuesto_local;
            $factura->uso_cfdi = $request->uso_cfdi;
            $factura->anio = $request->anio;
            $factura->zona_id = $request->zona;
            $factura->modulo_id = $request->modulo;
            $factura->facturada =0;
            $factura->factura_id = 0;
            $factura->contrato_id = $request->contrato;
            $factura->save();       
            
 
            $presupuestos = $request->presupuestos;
            foreach($presupuestos as $presupuesto)
            {
                $facturaD = new DetallesPrefacturas();
                $facturaD->prefactura_id = $factura->id;
                $facturaD->presupuesto_id = $presupuesto;
                $facturaD->save();   
            } 
            DB::commit();
            return response()->json(['message' => 'Se Guardo La Prefactura'], 200);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getmessage()], 499);
        }
    }
    public function GetMultiplesConceptos(Request $request){

        if (!$request->ajax()) return redirect('/');

        $presupuestos = $request->presupuestos;

        LOG::INFO($presupuestos);
        $detalleGeneraId = Presupuesto::whereIn("id",$presupuestos)->first();
        LOG::INFO($detalleGeneraId);
        $detallesGenerales=DetallesGenerales::find($detalleGeneraId->DetallesGenerales_id);
        LOG::INFO($detallesGenerales);
        $data=[
            'moduloId'=>$detallesGenerales->modulo_id,
            'zonaId'=>$detallesGenerales->zona_id,
            'contratoId'=>$detallesGenerales->contrato_id,
            'anio'=>$detallesGenerales->anio
        ];  
        $conceptos = Presupuesto::join('detallesgenerales','presupuestosnuevos.DetallesGenerales_id','=','detallesgenerales.id')
            ->join('vehiculos','detallesgenerales.Vehiculo_id','=','vehiculos.id')
            ->join('presupuesto_carrito','presupuestosnuevos.id','=','presupuesto_carrito.Presupuesto_id')
            ->select('vehiculos.no_economico','vehiculos.placas','presupuestosnuevos.Folio','detallesgenerales.Kilometraje_entrada','presupuestosnuevos.Mano_Obra_Descripcion',
                DB::raw('SUM(presupuesto_carrito.Cantidad * presupuesto_carrito.Venta) as importe')
                )->whereNull('presupuesto_carrito.deleted_at')->whereIn('presupuestosnuevos.id',$presupuestos)
                ->groupBy(
                    'vehiculos.no_economico',
                    'vehiculos.placas',
                    'presupuestosnuevos.Folio',
                    'detallesgenerales.Kilometraje_entrada',
                    'presupuestosnuevos.Mano_Obra_Descripcion'
                )->get();
        return response()->json(['conceptos' => $conceptos, 'data' => $data], 200);
    }
    public function GetPrefacturas(Request $request)
    {
        $prefacturas = Prefactura::with('usuario','cliente')
        ->select('prefacturas.*',
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta), 2) as subtotal'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta) * 0.16, 2) as iva'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta * 1.16), 2) as total'))
        ->join('detalles_prefacturas as detalles','detalles.prefactura_id','=','prefacturas.id')
        ->join('Presupuesto_Carrito as carrito','detalles.presupuesto_id','=','carrito.presupuesto_id')
        ->whereNull('carrito.deleted_at')
        ->whereNull('detalles.deleted_at')
        ->where('modulo_id',$request->modulo)
        ->where('anio',$request->anio)
        ->where('zona_id',$request->zona)
        ->where('contrato_id',$request->contrato)
        ->where('facturada', '!=', 2)
        ->orderby('id','desc')
        ->groupBy('prefacturas.id',
        'prefacturas.anio',
        'prefacturas.factura_id',
        'prefacturas.facturada',
        'prefacturas.modulo_id',
        'prefacturas.contrato_id',
        'prefacturas.zona_id',
        'prefacturas.empresa_id',
        'prefacturas.user_id',
        'prefacturas.fpago',
        'prefacturas.moneda',
        'prefacturas.mpago',
        'prefacturas.tipo_comprobante',
        'prefacturas.tipo_impuesto_local',
        'prefacturas.uso_cfdi',
        'prefacturas.created_at',
        'prefacturas.updated_at',
        'prefacturas.deleted_at',)
        ->get();
 
        return collect(['prefacturas' => $prefacturas]);
    }
    public function GetDetallesPrefactura(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id ;
        $prefactura=Prefactura::find($id);
        $empresa=Empresa::find($prefactura->empresa_id);
        $ids = DetallesPrefacturas::where('prefactura_id',$id)->pluck('presupuesto_id');
        return response()->json(['ids' => $ids,'prefactura'=>$prefactura,'empresa'=>$empresa],200);
    }
    public function DeletePreFactura(Request $request)
    {
        $Prefactura = Prefactura::findOrFail($request->id);
        $Prefactura->update(['facturada' => 2]);
        $Prefactura->delete();
        return response()->json(['message' => "Se Elimino Correctamente La Prefactura"]);
    }
    public function FacturaMulti(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $logotipo = '';
        $emisorid = 0;
        

        $emisor = $request->emisor; 
        if($emisor == 'Emisor3'){
            $logotipo = 'logo_cfb_fact.png';
            $emisorid = 1;
        }
        else if($emisor == 'Emisor4'){
            $logotipo = 'logo_akumas_fct.png';
            $emisorid = 2;
        } 
        else if($emisor == 'Emisor5'){
            $logotipo = 'logo_kmg.jpeg';
            $emisorid = 3;
        }else if($emisor == 'Emisor6'){
            $logotipo = 'karworks_logotipo_fact.jpeg';
            $emisorid = 4;
        }

 
        try{
            DB::beginTransaction();

            $factura = new Factura();
            $factura->empresa_id = $request->empresa;
            $factura->emisor_id = $emisorid;
            $factura->idusuario = Auth::user()->id;
            $factura->xml = '';
            $factura->pdf = '';
            $factura->estado = 'Registrado';
            $factura->movimiento = 'Facturacion';
            $factura->n_movimiento = '0';
            $factura->save();               
            
            $actualizarprefactura = false;
            $idprefacturaactualizar = 0;
            $presupuestos = $request->presupuestos;
            $presupuestosordenados = array_unique($presupuestos);
            sort($presupuestosordenados);

            $idsprefacturas=DetallesPrefacturas::whereIn('presupuesto_id',$presupuestos)->pluck('prefactura_id')->toArray();
            if(!empty($idsprefacturas)){
                $idsprefacturas = array_unique($idsprefacturas);
                if(count($idsprefacturas) == 1){
                    $prefactura=$idsprefacturas[0];
                    $idspresupuestosprefacturas=DetallesPrefacturas::where('prefactura_id',$prefactura)->pluck('presupuesto_id')->toArray();
                    $idspresupuestosprefacturas = array_unique($idspresupuestosprefacturas);
                    sort($idspresupuestosprefacturas); 
                    
                    if($presupuestosordenados == $idspresupuestosprefacturas){
                        $actualizarprefactura = true;
                        $idprefacturaactualizar = $prefactura;
                    }else{
                        if(empty(array_diff($idspresupuestosprefacturas, $presupuestosordenados))){
                            DetallesPrefacturas::where('prefactura_id',$prefactura)->delete();
                            Prefactura::where('id',$prefactura)->delete();
                        }else{
                            DetallesPrefacturas::where('prefactura_id',$prefactura)->WhereIn('presupuesto_id',$presupuestosordenados)->delete();
                        }
                    }
                }else{
                    foreach($idsprefacturas as $prefactura){
                        $idspresupuestosordenadosprefacturas=DetallesPrefacturas::where('prefactura_id',$prefactura)->pluck('presupuesto_id')->toArray();
                        $idspresupuestosordenadosprefacturas = array_unique($idspresupuestosordenadosprefacturas);
                        sort($idspresupuestosordenadosprefacturas);
                        if($presupuestosordenados == $idspresupuestosordenadosprefacturas && !$actualizarprefactura){
                            $actualizarprefactura = true;
                            $idprefacturaactualizar = $prefactura;
                        }else{
                            if(empty(array_diff($idspresupuestosordenadosprefacturas, $presupuestosordenados))){
                                DetallesPrefacturas::where('prefactura_id',$prefactura)->delete();
                                Prefactura::where('id',$prefactura)->delete();
                            }else{
                                DetallesPrefacturas::where('prefactura_id',$prefactura)->WhereIn('presupuesto_id',$presupuestosordenados)->delete();
                            }
                        }
                    
                    }
                }
            }



            $detalles_todo = [];

            $detalles=Presupuesto::join('detallesgenerales','presupuestosnuevos.DetallesGenerales_id','=','detallesgenerales.id')
            ->join('vehiculos','detallesgenerales.Vehiculo_id','=','vehiculos.id')
            ->join('presupuesto_carrito','presupuestosnuevos.id','=','presupuesto_carrito.Presupuesto_id')
            ->select('presupuestosnuevos.id','vehiculos.no_economico','vehiculos.placas','detallesgenerales.Kilometraje_entrada','presupuestosnuevos.Folio','presupuestosnuevos.Mano_Obra_Descripcion',
                DB::raw('SUM(presupuesto_carrito.Cantidad * presupuesto_carrito.Venta) as importe')
                )->whereNull('presupuesto_carrito.deleted_at')->whereIn('presupuestosnuevos.id',$presupuestos)
                ->groupBy(
                    'vehiculos.no_economico',
                    'vehiculos.placas',
                    'presupuestosnuevos.Folio',
                    'presupuestosnuevos.id',
                    'detallesgenerales.Kilometraje_entrada',
                    'presupuestosnuevos.Mano_Obra_Descripcion'
                )->get();

            foreach($detalles as $index => $presupuesto)
            {
                $detalles_todo[$index] = [
                    'id' => $presupuesto->id,
                    'codigo_sat' => '78181500',
                    'unidad_sat' => 'E48',
                    'unidad' => 'Unidad de servicio',
                    'economico' => $presupuesto->no_economico,
                    'placas' => $presupuesto->placas,
                    'kilometraje' => $presupuesto->no_economico,
                    'nsolicitud' => $presupuesto->Folio,
                    'descripcion' => $presupuesto->Mano_Obra_Descripcion,
                    'cantidad' => '1',
                    'precio' => $presupuesto->importe,
                  ]; 
            } 
            Log::info($detalles_todo);
            $dato = [];
            $dato['tipo_comprobante'] = $request->tipo_comprobante;
            $dato['uso_cfdi'] = $request->uso_cfdi;
            $dato['moneda'] = $request->moneda;
            $dato['fpago'] = $request->fpago;
            $dato['mpago'] = $request->mpago;

            $texto1=(($request->has('numauto') && $request->numauto !== null && $request->numauto !== '' && $request->numauto !== 'null')? "AUTORIZACION: ".$request->numauto:"CONTRATO: ".Contratos::find($request->contrato)->numero);
            $factura_emisor = FacturasEmisor::select(
                'id'
                ,'n_certificado'
                ,'archivo_cer'
                ,'archivo_key'
                ,'clave_key'
                ,'rfc_emisor as rfc'
                ,'nombre_emisor as nombre'
                ,'regimen_emisor as regimen'
                ,'codigo_emisor as codigo'
                ,'serie_factura as serie'
                ,'folio_factura as folio')->findorfail($emisorid);
        
            $empresa = Empresa::select('id','nombre','rfc','logo','cp','regimen')->where('id', $request->empresa)->first();

            $numero_certificado = $factura_emisor->n_certificado;
            $archivo_cer = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_cer;
            $archivo_key = public_path().'/utilerias/certificados/'.$factura_emisor->archivo_key;

            // generar y sellar un XML con los CSD de pruebas
            $cfdi = new Facturar();
            $docxml = $cfdi->crearXMLmas($empresa, $factura_emisor, $detalles_todo, $dato);          
            $keypem = new Certificados();
            $keypem->generaKeyPem($archivo_key,$factura_emisor->clave_key);
            $selladoxml = $cfdi->satxmlsv40_sella($docxml, $numero_certificado, $archivo_cer.'.pem',$archivo_key.'.pem');
            file_put_contents(public_path().'/facturas/factura.xml',$selladoxml);
            $nombrearchivoxml = public_path().'/facturas/factura.xml';

            $username = 'facturacion@aurumfixmotors.com';
            $password = 'Akumas2019##';
                  
            $invoice_path = $nombrearchivoxml;
            $xml_file = fopen($invoice_path, "rb");
            $xml_content = fread($xml_file, filesize($invoice_path));
            fclose($xml_file);
             
            $client = new \SoapClient('https://facturacion.finkok.com/servicios/soap/stamp.wsdl');
           
            $params = array(
            "xml" => $xml_content,
            "username" => $username,
            "password" => $password
            );

            $response = $client->__soapCall("stamp", array($params));
            $response2 = \Response::json($response->stampResult);
            if (isset($response->stampResult->Incidencias) && isset($response->stampResult->Incidencias->Incidencia)) {
                throw new \Exception($response->stampResult->Incidencias->Incidencia->MensajeIncidencia);
            }
           $folionvo = $factura_emisor->folio + 1;		
           $nombre = public_path().'/facturas/'.$factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.".xml";

          
         
           $file = fopen($nombre, "a");
           fwrite($file, $response->stampResult->xml);
           fclose($file);

           $pdfarch = $cfdi->generarPDFmas($nombre,$logotipo,$texto1);

           $napdf = $factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.'.pdf';
           $naxml = $factura_emisor->serie."".$folionvo."-".$factura_emisor->rfc."-".$response->stampResult->UUID.'.xml';
           
           $factAct = Factura::findOrFail($factura->id);
           $factAct->xml = $naxml;
           $factAct->pdf = $napdf;
           $factAct->save();
       
           $facteAct = FacturasEmisor::findOrFail($factura_emisor->id);
           $facteAct->folio_factura = $folionvo;
           $facteAct->save();


           Presupuesto::whereIn('id', $presupuestos)->update([
            'Factura_id' => $factura->id,
            'Status_id' => 5
            ]);

            
            if($actualizarprefactura){
                Prefactura::where('id',$idprefacturaactualizar)->update([
                'factura_id' => $factura->id,
                'facturada' => 1
                ]);
            }
            DB::commit();
            ob_end_clean(); 
            return response()->json(['message' => 'Se Facturo Correctamente'], 200);
        } catch (\Exception $e){
            DB::rollBack();
            log::info($e);
            return response()->json(['message' => $e->getMessage()],500);
        }
    }
    public function FacturarNomina(Request $request){
        
    }
    public function GetCodigo_Sat(Request $request){
        $data = CategoriasSat::findorfail($request->input("id"));
        return response()->json([
            'Code' => $data->codigo_sat
        ]);
    }
}
