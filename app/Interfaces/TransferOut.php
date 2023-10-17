<?php

namespace App\Interfaces;

interface TransferOut{
  public function index($data);


  public function create();


  public function store($request,$item_row);
  public function ms_store($request,$item_row);

  public function show($id);

  public function edit($id);

  public function update($request, $id);
  public function destroy($id);

}