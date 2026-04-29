@extends('skeleton')
@section('content')
    <h3>{{$title}}</h3>
    <hr>
    <div class="panel panel-primary">
        <div  class="panel-content">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-6"> <h4>Capital {{$boutique->nom}} : <span class="text-primary" id="capital"></span></h4></div>
                    <div class="col-md-6"><h4>Ventes {{date('Y')}} : <span class="text-primary" id="ventes"></span></h4></div>
                    <div class="col-md-6"><h4>Bénéfices {{date('Y')}} : <span class="text-primary" id="benefices"></span></h4></div>
                </div>
            </div>
        </div>
    </div>

    <h3 >Ventes des 6 derniers mois</h3>

    <div class="container-fluid">

        <canvas id="myChart"  height="250" width="800">
            <div class="container">
                <canvas id="myChart"></canvas>
            </div>
        </canvas>
    </div>

@endsection


@section('scripts')
    <script>

        $(function () {

            $.ajax({
                url:'{{route('get_infos_man')}}',
                beforeSend:function () {
                    startLoading();
                },
                success:function (data) {
                    stocks=data['stocks'];
                    ventes=data['ventes'];
                    factures=data['factures'];
                    months=data['months'];
                    ventesBM=data['ventesBM'];
                    cap=0;
                    ben=0;
                    for (i=0;i<stocks.length;i++){
                        cap+=stocks[i].quantite*stocks[i].produit.prix;
                    }
                    for (i=0;i<factures.length;i++){
                        for (j=0;j<factures[i].ventes.length;j++){
                            ben+=factures[i].ventes[j].quantite*factures[i].ventes[j].prix_achat;
                        }
                    }
                    ben=parseFloat(ventes)-ben;

                    $('#capital').html(money_format(cap.toFixed(2))+' {{trans('main.devise')}}');
                    $('#ventes').html(money_format(parseFloat(ventes).toFixed(2))+' {{trans('main.devise')}}');
                    $('#benefices').html(money_format(ben.toFixed(2))+' {{trans('main.devise')}}');
                    drawChart(months,ventesBM,'Ventes');

                },
                error:function () {
                    showAlert('Une erreur est survenue',1);
                },
                complete:function () {
                    stopLoading();
                }
            })


        });

        function money_format(value) {
            pt=value.split('.');
            arr=pt[0].split('');
            n=arr.length;
            res='';
            for(i=0;i<n;i++){
                if(i%3===0 && i>0)
                    res=' '+res;
                res=arr[n-i-1]+res;
            }
            if(pt.length===2)
                res+= '.'+pt[1];
            return res;
        }


        function drawChart(item,val,study_el) {
            console.log(item);
            let myChart = document.getElementById('myChart').getContext('2d');

            // Global Options
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 18;
            Chart.defaults.global.defaultFontColor = '#777';

            let massPopChart = new Chart(myChart, {
                type:'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data:{
                    labels:item,
                    datasets:[{
                        label:study_el,
                        data:val,
                        backgroundColor:'lightgreen',

                        borderWidth:1,
                        borderColor:'#777',
                        hoverBorderWidth:3,
                        hoverBorderColor:'#000'
                    }]
                },
                options:{
                    title:{
                        enable:true,
                        text:'Largest Cities In Massachusetts',
                        fontSize:25
                    },
                    legend:{
                        display:true,
                        position:'right',
                        labels:{
                            fontColor:'#000'
                        }
                    },
                    layout:{
                        padding:{
                            left:50,
                            right:0,
                            bottom:0,
                            top:0
                        }
                    },
                    tooltips:{
                        enabled:true
                    }
                }
            });
        }


</script>

@endsection
