<?php
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 14/08/2025
 * Time: 14:23
 */

 /* <table   class="table  table-bordered table-striped table-condensed table-inverse" id="tableProduits" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Stock Minimum</th>
                <th>Observations</th>
                <th>Statuts</th>
                <th>Action</th>
    --}}{{--  <th>@lang('main.prix_min')</th>
                <th>Prix de vente en détail</th>
                <th>Prix de vente en gros</th>
                <th>Prix de vente en semi-gros</th>
                <th>Prix de vente comptoir</th>
                <th>Prix d'achat</th>--}}{{--*/

        /*    </tr>

            </thead>
            <tbody>
            <?php $i=1; */?><!--

            @foreach($produit as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$p->reference}}</td>

                    <td><a href="#edit_pdt" data-toggle="modal" data-backdrop="false" onclick="edit_pdt('{{$p->id}}','{{sanitize($p->libelle)}}')"><strong ><span class="glyphicon glyphicon-edit"></span> {{$p->libelle}}</strong></a></td>

                    <td>
                        @foreach($p->securite as $sec)
                            {{ $sec->stock_minimum }}
                        @endforeach
                        --}}{{--{{$p->securite->stock_minimum}}--}}{{--
                    </td>
                    <td>
                        @foreach($p->securite as $sec)
                        {{ $sec->observation }}
                        @endforeach
                    </td>
                    <td></td>
                    @foreach($p->securite as $sec)
                    <td><a href="#edit_pdt2" data-toggle="modal" data-backdrop="false" onclick="edit_pdt2('{{$sec->id_produit}}','{{$sec->stock_minimum}}','{{$sec->observation}}')"><strong ><span class="glyphicon glyphicon-edit"></span>Modifier</strong></a></td>
                    @endforeach
                   --}}{{-- <td>{{$p->prix_minimum}} @lang('main.devise')</td>
                    <td>{{$p->prix}} @lang('main.devise')</td>
                    <td>{{$p->prix_gros}} @lang('main.devise')</td>
                    <td>{{$p->prix_semi_gros}} @lang('main.devise')</td>
                    <td>{{$p->prix_comptoir}} @lang('main.devise')</td>
                    <td>{{$p->prix_achat}} @lang('main.devise')</td>--}}{{--
                </tr>
            @endforeach
            </tbody>
        </table>-->
        ?>