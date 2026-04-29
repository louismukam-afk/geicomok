@extends('skeleton')
@section('content')

    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
    <div class="col-md-12">
        <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

    </div>

    <h3>Stocks des produits : ({{((new DateTime(date('Y-m-d')))->format('d-m-Y'))}})</h3>
    <div class="col-md-10">


        <table   class="table  table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Stock</th>
            </tr>

            </thead>
            <tbody id="stock-container">

            </tbody>
        </table>

    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            sCont=$('#stock-container');
            sCont.empty();
           $.ajax({
               url:'{{route('get_stock_ajax')}}',
               beforeSend:function () {
                 startLoading();
               },
               success:function (data) {
                   produits=data['produits'];
                    str='';
                   for (i=0;i<produits.length;i++){
                       str='  <tr>'+
                           '<td>'+ (i+1) +'</td>'+
                           '<td><a href="#"> <strong>'+ produits[i].libelle +'</strong></a></td>'+

                          ' <td>'+
                           produits[i].categorie.libelle +
                           '</td>'+
                           '<td><strong>'+ produits[i].stock.quantite +'</strong></td>'+
                           '</tr>';
                       sCont.append(str);

                   }

               },
               error:function () {
                   showAlert('Une erreur est survenue',1);
               },
               complete:function () {
                   stopLoading();
               }
           })


        });
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stock</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
