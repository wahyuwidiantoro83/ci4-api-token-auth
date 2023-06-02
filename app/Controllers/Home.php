<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Home extends BaseController
{
    public function index()
    {
        $barang = new BarangModel();
        $data['barang'] = $barang->select('*')->findAll();
        return view('welcome_message', $data);
    }
}
