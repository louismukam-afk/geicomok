{{--
@extends('skeleton')
@section('content')
@include('perso.functions')

    <div class="col-md-8">


        <table class="table table-bordered  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Quantité </th>
            </tr>

            </thead>
            <tbody id="stock-container">

            </tbody>
        </table>


        <div class="modal fade" id="edit_stock">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">




                        <form accept-charset="UTF-8" id="form_edit_stock" role="form" method="POST" action="{{route('update_stock')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit-id" name="id" >
                            <fieldset>

                              <h3 id="libelle-produit"></h3>
                                <h3><strong id="stock-produit"></strong></h3>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="operation">Opération: </label>
                                        <select name="operation" id="operation" form="form_edit_stock" class="form-control" required>
                                            <option value="1">Ajouter</option>
                                            <option value="0">Soustraire</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-quantite"> Quantité :  </label>
                                        <input class="form-control" id="edit-quantite" name="quantite" type="number" required>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <button class="btn btn-success pull-right" style="margin-top: 20px"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>

                                </div>


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>


    </div>

@endsection

@section('scripts')
    <script>

        $(function () {
            sCont=$('#stock-container');
            sCont.empty();
            $.ajax({
                url:'{{route('get_stock_admin')}}',
                beforeSend:function () {
                    startLoading();
                },
                success:function (data) {
                    produits=data['produits'];
                    str='';
                    for (i=0;i<produits.length;i++){
                        str='  <tr>'+
                            '<td>'+ (i+1) +'</td>'+
                            '<td><a href="#edit_stock" data-toggle="modal" data-backdrop="false" onclick="edit_stock('+ produits[i].stock.id +',\''+ sanitize(produits[i].libelle) +'\',\''+ produits[i].stock.quantite +'\')"><strong ><span class="glyphicon glyphicon-edit"></span> '+ produits[i].libelle +' </strong> </a></td>'+


                            '<td><strong id="sq'+ produits[i].stock.id +'">'+ produits[i].stock.quantite +'</strong></td>'+
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

        $('#form_edit_stock').on('submit',function (e) {
           e.preventDefault();
           form=$(this);

            $.ajax({
                url:'{{route('update_stock_ajax')}}',
                type:'GET',
                data:{
                    id:$('#edit-id').val(),
                    operation:$('#operation').val(),
                    quantite:$('#edit-quantite').val()
                },
                beforeSend:function () {
                    startLoading();
                },
                success:function (data) {

                    stock=data['stock'];
                    $('#sq'+stock.id).html(stock.quantite);
                    $('#edit_stock').modal('hide');
                   // showAlert('Stock modifié',0);

                },
                error:function (data) {
                    error=data.responseJSON;
                    if(error){
                        showAlert(error,1);

                    }else
                        showAlert('Une erreur est survenue',1);
                },
                complete:function () {
                    stopLoading();
                    form[0].reset();

                }
            })

        });





        function edit_stock(id,libelle,stock) {
            $('#edit-id').val(id);
            $('#libelle-produit').html(libelle);
            $('#stock-produit').html(stock);
        }


    function sanitize(str) {
        str=str.replace(/\'/g,'\\\'');
        str=str.replace(/\n/g,'\\n');
        return str;
    }

    </script>


@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
--}}