@extends('voyager::master')

@section('page_title', 'hola')

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
          
            <div class="col-md-12">
                
                <div class="panel panel-bordered">
                    <div class="row">
                        <br>
                        <div class="col-xs-4 col-sm-4 text-right">
                            
                        </div>
                        <div class="col-xs-4 col-sm-4 text-center">
                           
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            
                        </div>
                        <div class="col-md-6 col-sm-6">
                            
                        </div>
                        <div class="col-md-6 col-sm-6 text-right">
                            
                        </div>
                    </div>
                </div>
                <input type="text" class="form-control" id="input">

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <div class="panel panel-bordered">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                       
                                    </div>
                                </div>
                                <audio id="audio" controls>
                                    <source type="audio/wav" src="{{ asset('sound/notification.mp3') }}">
                                </audio>
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="table-visitor">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px">N&deg;</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

 
@stop

@section('css')
    <style>
        @font-face {
            font-family: 'Seven Segment';
            src: url({{ asset('fonts/Seven-Segment.ttf') }});
        }
        .td-actions img{
            filter: grayscale(100%);
        }
        .td-actions img:hover{
            filter: grayscale(0%);;
            /* width: 28px */
        }
        .img-avatar{
            width: 30px;
            height: 30px;
            border-radius: 15px;
            margin-right: 5px
        }
        #label-score{
            font-family: 'Seven Segment';
            font-size: 100px
        }
        #timer{
            font-family: 'Seven Segment';
            font-size: 60px;
            color: #E74C3C
        }
    </style>
@endsection

@section('javascript')
    <script src="timbre.js"></script>
    <script src="{{ asset('js/timbre.js') }}"></script>
    <script src="{{ asset('js/timbre.dev.js') }}"></script>
    <script>
        $(document).ready(function() {
            const audio = new Audio("https://manzdev.github.io/codevember2017/assets/eye-tiger.mp3");
            audio.play();
        });

        // var sonido = new Audio();
        var audio = document.getElementById("audio");

        //al cargar la ventana
        window.onload = function() {

            //opcion de html5 para pedir permisos en el navegador para la notificacion
            Notification.requestPermission(function(permission){

            //opciones de la notificacion
            var opciones = {
                    body: "El texto que quiera en la notificacion",
                    icon: "https://t2.gstatic.com/licensed-image?q=tbn:ANd9GcQdAnprsidzbOSZ4jI1SvcFeIEuFKwBLrILGo8tLCEA4ixMzfxUQfk6onBDhipea4sD"
                };
            var notification = new Notification("EL TITULO DE LA ",opciones);
            });//finaliza la notificacion
        }//finaliza la carga de la ventana
    </script>
@stop
