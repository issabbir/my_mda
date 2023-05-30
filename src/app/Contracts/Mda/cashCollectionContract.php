<?php
namespace App\Contracts\Mda;


interface cashCollectionContract
{
    public function cashCollectionCud($action_type=null, $request = [], $id=null);
    public function cashCollectionDatatable();
    public function approvedCollections();
    public function cashCollectionDetail($id);
}
