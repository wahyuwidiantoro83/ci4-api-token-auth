<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use \Codeigniter\Shield\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Config\Pager;

use function PHPUnit\Framework\isNull;

class ApiController extends BaseController
{
    protected $barang;
    public function __construct()
    {
        $this->barang = new BarangModel();
    }
    public function get_barang()
    {
        $status = $this->request->getVar('status');
        $key = $this->request->getVar('key');

        if (!is_null($key)) {
            if ($status === '1' || $status === '0') {
                $barang = $this->barang->where('status', $status)->like('nama', strtolower($key), 'both')->paginate(5, 'barang');
                $pager = $this->barang->pager;
                return response()->setStatusCode(200)->setJSON(['status' => 'success', 'data' => $barang, 'page' => $pager->getDetails('barang')]);
            }
            $barang = $this->barang->like('nama', strtolower($key), 'both')->paginate(5, 'barang');
            $pager = $this->barang->pager;
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'data' => $barang, 'page' => $pager->getDetails('barang')]);
        }

        if ($status === '1' || $status === '0') {
            $barang = $this->barang->where('status', $status)->paginate(5, 'barang');
            $pager = $this->barang->pager;
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'data' => $barang, 'page' => $pager->getDetails('barang')]);
        }

        $barang = $this->barang->paginate(5, 'barang');
        $pager = $this->barang->pager;
        return response()->setStatusCode(200)->setJSON(['status' => 'success', 'data' => $barang, 'page' => $pager->getDetails('barang')]);
    }
    public function insert_barang()
    {
        if (!$this->validate([
            'nama' => [
                'rules' => 'required'
            ],
            'harga' => [
                'rules' => 'required'
            ],
            'satuan' => [
                'rules' => 'required'
            ]
        ])) {
            return response()->setStatusCode(400)->setJSON(['status' => 'fail', 'message' => 'Data tidak sesuai atau kurang lengkap']);
        }
        $nama = $this->request->getPost('nama');
        $harga = $this->request->getPost('harga');
        $satuan = $this->request->getPost('satuan');
        $gambar = $this->request->getFile('gambar');

        if ($gambar->getError() == 4) {
            $namagambar = 'default.png';
        } else {
            $namagambar = $gambar->getRandomName();
            //pindahkan file sampul
            $gambar->move('image', $namagambar);
        }

        $data = [
            'nama' => $nama,
            'harga' => $harga,
            'satuan' => $satuan,
            'gambar' => $namagambar
        ];

        if ($this->barang->save($data)) {
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Data barang berhasil ditambah', 'data' => [
                'id' => $this->barang->getInsertID(),
                'nama' => $nama
            ]]);
        } else {
            return response()->setStatusCode(400)->setJSON(['status' => 'fail', 'message' => 'Gagal menambahkan data barang']);
        }
    }
    public function update_barang()
    {
        if (!$this->validate([
            'nama' => [
                'rules' => 'required'
            ],
            'harga' => [
                'rules' => 'required'
            ],
            'satuan' => [
                'rules' => 'required'
            ]
        ])) {
            return response()->setJSON(['status' => 'fail', 'message' => 'Data tidak sesuai atau kurang lengkap']);
        }
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $harga = $this->request->getPost('harga');
        $satuan = $this->request->getPost('satuan');
        $gambar = $this->request->getFile('gambar');

        if ($gambar->getError() == 4) {
        } else {
            $namagambar = $gambar->getRandomName();
            //pindahkan file sampul
            $gambar->move('image', $namagambar);
        }

        $data = [
            'nama' => $nama,
            'harga' => $harga,
            'satuan' => $satuan,
            'gambar' => $namagambar
        ];

        if ($this->barang->find($id)) {
            $this->barang->update($id, $data);
            $returndata = $this->barang->find($id);
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => ['id' => $returndata['id'], 'nama' => $returndata['nama']]]);
        } else {
            return response()->setStatusCode(400)->setJSON(['status' => 'fail', 'message' => 'Data gagal diupdate']);
        }
    }

    public function get_barang_byId($id)
    {
        // dd(boolval($this->barang->find($id)));
        if ($this->barang->find($id)) {
            $barang = $this->barang->find($id);
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'data' => $barang]);
        } else {
            return response()->setStatusCode(404)->setJSON(['status' => 'fail', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function delete_barang($id)
    {
        if ($this->barang->delete($id)) {
            return response()->setStatusCode(200)->setJSON(['status' => 'success', 'message' => 'Data ' . $id . ' berhasil dihapus']);
        } else {
            return response()->setStatusCode(400)->setJSON(['status' => 'fail', 'message' => 'Data ' . $id . ' gagal dihapus']);
        }
    }
}
