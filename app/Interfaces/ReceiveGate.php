<?php

namespace App\Interfaces;

interface ReceiveGate{
  public function index($data);


  public function create();


  public function store($request);

  public function show($id);

  public function edit($id);

  public function update($request, $id);
  public function destroy($id);

}