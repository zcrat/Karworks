<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Recepción de Vehículo1</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('css/estilos_impresion.css')}}" media="print">
    <link rel="stylesheet" href="{{asset('css/estilos4.css')}}">
    <script language="javascript">
        function imprSelec(nombre) {
            var ficha = document.getElementById(nombre);
            var ventimp = window.open(' ', 'popimpr');
            ventimp.document.write(ficha.innerHTML);
            ventimp.document.close();
            ventimp.print();
            ventimp.close();
            window.close();
        }
    </script>
</head>
<body>
    @php
        $combustible = [
            0 => "LL",
            1 => "3/4",
            2 => "1/2",
            3 => "1/4",
        ];
        $condiciones = [
            2 => "✔",
            3 => "O",
            4 => "F",
            5 => "D",
            6 => "R",
            7 => "N/A",
        ];
        $labels=[
            'Llantas de Refacción',
            'Cubreruedas',
            'Candado de Ruedas',
            'Gato',
            'Llave para Tuercas de Rueda',
            'Triángulo de Seguridad',
            'Extinguidor',
            'Cables para Corriente',
            'Estuche de Herramientas',
            'Tarjeta de Circulación',
            'Placas'
        ];
        $keys=[
            'llanta',
            'cubreruedas',
            'candado_ruedas',
            'gato',
            'llave_tuercas',
            'triangulo_seguridad',
            'extinguidor',
            'cables_corriente',
            'estuche_herramientas',
            'tarjeta_circulacion',
            'placas',
        ];
        $labels2=[
            'Decolorada',
            'Logos en buen estado',
            'Color no igualado',
            'Exceso de rociado',
            'Exceso de rayones',
            'Daños por granizo',
            'Pequeñas grietas',
            'Lluvia ácida',
            'Carroceria con golpes',
            'Emblemas completos',
        ];
        $keys2=[
            'decolorada',
            'logos',
            'color_no_igual',
            'exeso_rociado',
            'exeso_rayones',
            'danios_granizado',
            'pequenias_grietas',
            'lluvia_acido',
            'carroceria_golpes',
            'emblemas_completos',
        ];
    @endphp
    <div class="  ggg">
        <div class="titulo">
            <h5 class="cent titulo-principal">Reporte de Recepción de Vehículo</h5>
        </div>
        <div class="iden"><h6 class="titulo__folio">No: <span
                    class="text--rojo ">{{$RecepcionVehicular->detallesGenerales->OrdenServicio}}</span></h6></div>
        <div class="completo col-md- ">
            <div class="bloque__uno  ">
                <div class="l-3 u1 border-row twolineas"><label for="text">Nombre</label> <label for="text"><span class="table--text ">{{$RecepcionVehicular->detallesGenerales->Empresa->nombre}} </span></label></div>
                <div class=" l-2 uno-bor twolineas"><label for="text">Cliente/Zona/Usuario</label> <label for="text"><span class="table--text ">{{$RecepcionVehicular->detallesGenerales->Customer->nombre}} </span></label></div>
                <div class="border-block border-col twolineas"><label for="text">OdeS</label> <label for="text"><span class="table--text ">{{$RecepcionVehicular->detallesGenerales->OrdenSeguimiento}}</span></label></div>
                <div class="l-6 border-row border-inline"><label for="text">Dirección</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->direccion}}</label></div>
                <div class="l-2 border-row border-inline twolineas"><label for="text">Ciudad</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->ciudad}}</span> </label></div>
                <div class="twolineas border-row border-col"><label for="text">Estado</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->estado}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">C.P.</label> <label for="text"><span  class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->cp}} </span> </label></div>
                <div class="twolineas border-row"><label for="text">Contacto</label> <label for="text"> <span  class="table--text">{{$RecepcionVehicular->detallesGenerales->contacto}}</span></label></div>
                <div class="twolineas border-row border-inline"><label for="text">Tel. Contacto</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->contacto_tel}}</span></label></div>
                <div class="twolineas l-3 border-row border-inline"><label for="text">Email</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->email}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Tel. Negocio</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Empresa->tel_negocio}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Gas. Entrada</label> <label for="text"><span class="table--text">{{ $combustible[$RecepcionVehicular->detallesGenerales->Gas_entrada]?? ''}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Gas. Salida</label> <label for="text"><span class="table--text">{{ $combustible[$RecepcionVehicular->detallesGenerales->Gas_salida]?? ''}}</span></label></div>
                <div class="twolineas border-row border-inline"><label for="text">Año</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->anio}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Marca</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->marca->nombre}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Modelo</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->modelo->nombre}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Color</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->color->nombre}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">Placas</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->placas}}</span></label></div>
                <div class="twolineas border-row border-col"><label for="text">#Económico</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->no_economico}}</span></label></div>
                <div class=" u4 level--2 border-col"><label for="text">KM Entrada</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Kilometraje_entrada}}</span></label></div>
                <div class=" level--2 border-row border-col"><label for="text">KM Salida</label> <label for="text"><span  class="table--text">{{$RecepcionVehicular->detallesGenerales->Kilometraje_salida}}</span></label></div>
                <div class=" l-2 u3"><label for="text">VIN</label> <label for="text"><span  class="table--text">{{$RecepcionVehicular->detallesGenerales->Vehiculo->vim}}</span></label></div>
            </div>
        </div>
        <div class="bloque___dos    bord ">
             @if($RecepcionVehicular->detallesGenerales->modulo->factura_emisor_id == 4 )

                <div class="imagen_logo_arriba">
                    <img src="{{asset('img/'.$RecepcionVehicular->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor)}}" alt="Logotipo">
                    <p>{{$RecepcionVehicular->detallesGenerales->modulo->FacturaEmisor->nombre_emisor}}<br> S.A. DE .C.V. <br>
                        VILLAS DEL MONTE #45, COL. DESARROLLO MONARCA. C.P. 58350 MORELIA, MICH, TEL (433) 4134234
                        
                    </p>
                </div>
                @else
                 <div  class="imagen_logo_arriba">
                    <img src="{{asset('img/'.$RecepcionVehicular->detallesGenerales->modulo->FacturaEmisor->logotipo_emisor)}}" alt="Logotipo Akumas">
                    <p>{{$RecepcionVehicular->detallesGenerales->modulo->FacturaEmisor->nombre_emisor}}, S.A. DE .C.V. <br>
                        PUERTO DE ACAPULCO #328, COL. RINCON DEL ANGEL. C.P. 58337 <br>
                        MORELIA, MICH, TEL (433) 2532182
                        
                    </p>
                </div>
                @endif



            <div class=" fg"><label for="text">Ubicacion</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Ubicacion}}</span></label></div>
            <div class=" fg"><label for="text">Escrito por</label> <label for="text"> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->user->name}}</span> </label></div>
            <div class=" fg"><label for="text">Recibido </label> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Fecha_entrada}}</span></div>
            <div class=" fg"><label for="text">Compromiso para</label> <span class="table--text">{{$RecepcionVehicular->detallesGenerales->Fecha_Esperada}}</span></div>
            <div class=" fg"><label for="text">Salida</label> <label for="text"><span class="table--text">{{$RecepcionVehicular->detallesGenerales->Fecha_salida}}</span></label></div>
            <div class=" fg mas_div"><label for="text">Técnico</label><label for="text"><span class="table--text">{{$RecepcionVehicular->tecnico->nombre}}</span></label></div>
            <div class=" di"><label for="text">Firma de Supervisión</label>
                <img src="{{asset('/storage/firmastaller/'.$RecepcionVehicular->Firma.'')}}" width="100%" height="50px"/>
            </div>
        </div>
        <div class="guia bor">
            <div>D = Dañada</div>
            <div>✔ =Sin daño visible</div>
            <div>O = Operacional</div>
            <div>R = Reparación necesaria</div>
            <div>F = Falta objeto</div>
            <div>N/A = No aplica</div>
        </div>
        <div class="titulo-50">
            <h6 class="titulo-principal">Condiciones de Interiores y Equipo</h6>
        </div>
        @foreach ($RecepcionVehicular->interiores as $i)
            <div class="interiores">
                <div class="bor">
                    <div class="contenedor-ifs">
                        <div></div>
                        <div><p class="cent">IF</p></div>
                        <div><p class="cent">IT</p></div>
                        <div><p class="cent">DF</p></div>
                        <div><p class="cent">DT</p></div>
                        <div><p class="text-right">Paneles de Puertas</p></div>
                        <div class="bb">{{$condiciones[$i->puerta_interior_frontal] ?? ''}}</div>
                        <div class="bb">{{$condiciones[$i->puerta_interior_trasera]?? ''}}</div>
                        <div class="bb">{{$condiciones[$i->puerta_delantera_frontal]??''}}</div>
                        <div class="bb">{{$condiciones[$i->puerta_delantera_trasera]??''}}</div>
                        <div><p class="text-right">Asientos</p></div>
                        <div class="bb">{{$condiciones[$i->asiento_interior_frontal]??''}}</div>
                        <div class="bb">{{$condiciones[$i->asiento_interior_trasera]??''}}</div>
                        <div class="bb">{{$condiciones[$i->asiento_delantera_frontal]??''}}</div>
                        <div class="bb">{{$condiciones[$i->asiento_delantera_trasera]??''}}</div>
                    </div>
                    <div class="similares ">
                        <div class="text-right"><label class="level-1  " for="text">Consola Central</label></div>
                        <div class="bb">{{$condiciones[$i->consola_central]}}</div>
                        <div class="text-right"><label class="level-2 " for="text">Claxon</label></div>
                        <div class="bb">{{$condiciones[$i->claxon]}}</div>
                        <div class="text-right"><label class=" " for="text">Tablero</label></div>
                        <div class="bb">{{$condiciones[$i->tablero]}}</div>
                        <div class="text-right"><label class=" " for="text">Quemacocos</label></div>
                        <div class="bb">{{$condiciones[$i->quemacocos]}}</div>
                        <div class="text-right"><label class=" " for="text">Toldo</label></div>
                        <div class="bb">{{$condiciones[$i->toldo]}}</div>
                        <div class="text-right"><label class=" " for="text">Elevadores Eléctricos</label></div>
                        <div class="bb">{{$condiciones[$i->elevadores_eletricos]}}</div>
                        <div class="text-right"><label class=" " for="text">Luces Interiores</label></div>
                        <div class="bb">{{$condiciones[$i->luces_interiores]}}</div>
                        <div class="text-right"><label class=" " for="text">Seguros Eléctricos</label></div>
                        <div class="bb">{{$condiciones[$i->seguros_eletricos]}}</div>
                        <div class="text-right"><label class=" " for="text">Tapetes </label></div>
                        <div class="bb">{{$condiciones[$i->tapetes]}}</div>
                        <div class="text-right"><label class=" " for="text">A.C./Climatizador </label></div>
                        <div class="bb">{{$condiciones[$i->climatizador]}}</div>
                        <div class="text-right"><label class="" for="text">Radio</label></div>
                        <div class="bb">{{$condiciones[$i->radio]}}</div>
                        <div class="text-right"><label class="" for="text">Espejo Retrovisor</label></div>
                        <div class="bb">{{$condiciones[$i->espejos_retrovizor]}}</div>
                    </div> <!--borde IFS FIN-->
                </div> <!--interiores-->
            </div>
            @break
        @endforeach
        @foreach ($RecepcionVehicular->exteriores as $e)
            <div class="exteriores">
                <h6 class="titulo-principal">Condiciones de Exteriores y Equipo</h6>
            </div>
            <div class="bloque-r-50 layo">  <!-- bloque r 50-->
                <div class="similares"> <!-- similares r 50-->
                    <div><label class="level-1 text--right" for="text">Antena/radio</label></div>
                    <div class="bb">{{$condiciones[$e->antena_radio]}}</div>
                    <div><label class="level-2 text--right" for="text">Estribos</label></div>
                    <div class="bb">{{$condiciones[$e->estribos]}}</div>
                    <div><label class=" text--right" for="text">Antena/teléfono</label></div>
                    <div class="bb">{{$condiciones[$e->antena_telefono]}}</div>
                    <div class="fl"><label class=" text--right" for="text">Guardafangos</label></div>
                    <div class="bb">{{$condiciones[$e->guardafangos]}}</div>
                    <div><label class=" text--right" for="text">Antena/C.B.</label></div>
                    <div class="bb">{{$condiciones[$e->antena_cb]}}</div>
                    <div><label class=" text--right" for="text">Parabrisas</label></div>
                    <div class="bb">{{$condiciones[$e->parabrisas]}}</div>
                    <div><label class=" text--right" for="text">Sist. de Alarma</label></div>
                    <div class="bb">{{$condiciones[$e->sistema_alarma]}}</div>
                    <div><label class=" text--right" for="text">Limpiaparabrisas</label></div>
                    <div class="bb">{{$condiciones[$e->limpia_parabrisas]}}</div>
                    <div><label class=" text--right" for="text">Luces Exteriores</label></div>
                    <div class="bb">{{$condiciones[$e->luces_exteriores]}}</div>
                    <div><label class=" text--right" for="text">Espejos Laterales</label></div>
                    <div class="bb">{{$condiciones[$e->espejos_laterales]}}</div>
                </div> <!-- bloque r 50-->
            </div>   <!-- similares r 50-->
            @break
        @endforeach
        @foreach ($RecepcionVehicular->inventario as $ei)
            <div class="bloque-varios ">  <!--  varios-->
                <div><h6 class="titulo-principal">Varios Equipos - Inventario</h6></div>
                <div class="g-3">
                    <div><p>SI </p></div>
                    <div><P>NO</P></div>
                    <div></div>
                    @foreach(range(0, count($keys) - 1) as $index)
                        <div class="cuadrado">{{ $ei->{$keys[$index]} == 1 ? "✔" : "" }}</div>
                        <div class="cuadrado">{{ $ei->{$keys[$index]} == 0 ? "✔" : "" }}</div>
                        <div><p>{{ $labels[$index] }}</p></div>
                    @endforeach
                </div> <!--g3-->
            </div> <!--  varios-->
            @break
        @endforeach
        @foreach ($RecepcionVehicular->pintura as $cp)
            <div class="level--2"><h6 class="titulo-principal">Condiciones de Pintura</h6></div>
            <div class="bloque-r-50 level--2 row--3"> <!-- contenedor pintura  50 r-->
                <div class="bloque-4"> <!-- bloque-cond-int -->
                    <div><label class="level-1" for="text">SI</label></div>
                    <div><label class="level-1" for="text">NO</label></div>
                    <div></div>
                    <div><label class="level-2" for="text">SI </label></div>
                    <div><label class="level-2" for="text">NO</label></div>
                    <div></div>
                    @foreach(range(0, count($keys2) - 1) as $index)
                        <div class="cuadrado">{{ $cp->{$keys2[$index]} == 1 ? "✔" : "" }}</div>
                        <div class="cuadrado">{{ $cp->{$keys2[$index]} == 0 ? "✔" : "" }}</div>
                        <div><p>{{ $labels2[$index] }}</p></div>
                    @endforeach
                </div> <!-- bloque-cond-int -->
            </div><!-- contenedor pintura  50 r-->
            @break
        @endforeach
        <div class="layo-car bor "> <!--carro -->
            <div class="ffond">
                <img src="{{asset('/storage/carros/'.$RecepcionVehicular->Carro.'')}}" alt="Tipo de Vehiculo" id="v" alt="Carros" width="100%" height="156"usemap="#carro_8" border="0"/>
            </div>
            </div> <!--carro -->
            <div class="nnno bor"> <!--final content -->
                <div class="notas col-md-12 fl ">
                    <p>Notas:<span class="table--text"> {{$RecepcionVehicular->Notas}}</span>
                    </p>
                </div>
                <div class="leyenda  bor"> <!--leyenda -->
                    <p class="lll">
                        Hemos registrado los daños en su vehículo que no están relacionados con las reparaciones
                        autorizadas.
                        El que usted y nuestro representante hayan revisado estas áreas conjuntamente,
                        ambos podemos tener la seguridad del mejor servicio posible. Hemos indicado cada área de daño o
                        defecto,
                        junto con otros artículos diversos, por favor no dude en ayudarnos mientras llenamos este formato.
                    </p>
                    <div class="recibido cent">
                        {{--todo ruta imagen--}}
                        <img src="{{asset('/storage/firmastaller/'.$RecepcionVehicular->Firma)}}"
                            width="100%"
                            height="80px"/>
                        <p>Firma de Recibido</p></div>
                    <div class=" cliente cent ">
                        <p class="arriba-abajo">___________________________________ <br> <br> Firma del Cliente</p>
                    </div>
                </div><!--leyenda -->
                <div class="indicaciones  bor ">
                    <p>
                    <span class="table--text">
                        Reporte de Fallas:
                    </span>
                    {{$RecepcionVehicular->detallesGenerales->Indicaciones_cliente}}
                    </p>
                </div>
            </div>
        </div><!--final content -->
    </div> <!--container -->
    <div class="fi">
        <a class="" href="#">
            <button onclick="window.print()"><img src="{{asset('img/imprime.jpg')}}" height="50"/></button>
        </a>
    </div>
</body>
</html>