<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ setting('admin.title') }} | Notificación de pago</title>
        
        <!-- Favicon -->
        <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_favicon == '')
            <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
        @else
            <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
        @endif
    </head>
    <body>
        <style>
            :root{
                --color-dark-500: #000;
                --color-dark-100: #555;
                --color-litgh-100: #fff;
                --color-primary: rgb(51, 127, 51);
            }
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body{
                font-family:  Arial, sans-serif;
                background: linear-gradient(355deg,#2b580c, #95ec2b);;
            }
            .container{
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 10px;
            }
            .card{
                width: 100%;
                margin: 1rem;
                /* margin-right: 1rem; */
                border: 1px solid var(--color-dark-100);
                border-radius: 20px;
                background-color: var(--color-litgh-100);
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            }
            .card-header{
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: var(--color-primary);
                border-top-right-radius: 20px;
                border-top-left-radius: 20px;
                padding: 10px 0;
                color: var(--color-litgh-100);
            }
            .card-body{
                padding: 0px 15px;
                padding-top: 20px;
                padding-bottom: 10px;
                
            }
            .card-body .body-main{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                
            }
            .body-main p{
                padding-bottom: 1rem;
                font-size: 1.2rem;
                font-weight: 500;
            }
            .body-main .money{
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }
            .body-main .money span{
                color: var(--color-primary);
            }
            .body-main .money span{
                color: var(--color-primary);
            }
            .body-main .datetime{
                font-size: 0.8rem;
                color: var(--color-dark-100);
            }
            .dotted-line {
                border-top: 1px dashed #000; /* Establece el ancho, el estilo y el color de la línea */
                width: 100%;
                margin: 0 auto; /* Centra la línea en el contenedor */
                margin-bottom: 10px;
            }
            .logo{
                height: 35px;
                margin-right: 5px;
            }
            .group-table{
                margin-left: 5px;
                margin-bottom: 15px;
            }
            .group-table p{
                font-size: 0.8rem;
                margin-bottom: 3px;
            }
            .group-table .account{
                font-weight: 700;
                color: var(--color-dark-100);
            }
            .group-table .name{
                font-weight: 700;
            }
            .group-table .number-account{
                color: var(--color-dark-100);
            }

            .card-footer{
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
                color: var(--color-dark-100);
                font-size: 1rem;
                font-weight: 700;
            }
        </style>
        @php
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
        @endphp
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <img src="{{ $admin_favicon == '' ? asset('images/icon.png') : Voyager::image($admin_favicon) }}" alt="logo" class="logo">
                    <h3>RECIBO</h1>
                </div>
                <div class="card-body">
                    <div class="body-main">
                        <p class="msj">Pago Exitoso!</p>
                        <p class="money"><span>Bs</span> {{ $payment->amount }}</p>
                        <p class="name-empresa">{{ setting('admin.title') }}</p>
                        <p class="datetime">{{ date('d', strtotime($payment->created_at)) }} DE {{ Str::upper($months[intval(date('m', strtotime($payment->created_at)))]) }}, {{ date('Y H:i', strtotime($payment->created_at)) }}</p>

                    </div>
                    <hr class="dotted-line">
                    <div class="group-table">
                        <p class="account">Titular del préstamo</p>
                        <p class="name">{{ $payment->pawn->person->first_name }} {{ $payment->pawn->person->last_name1 }} {{ $payment->pawn->person->last_name2 }}</p>
                        <p class="number-account">
                            <span>CI: </span> {{ $payment->pawn->person->ci ?? 'No definido' }}
                        </p>
                    </div>
                    <div class="group-table">
                        <p class="account">Atendido por</p>
                        <p class="name">{{ $payment->user->name }}</p>
                    </div>
                </div>
                <div class="card-footer">
                    <p>RECIBO N° {{ str_pad($payment->id, 6, "0", STR_PAD_LEFT) }}</p>
                </div>
            </div>
            
        </div>
        
    </body>
</html>