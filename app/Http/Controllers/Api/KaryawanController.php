<?php namespace App\Http\Controllers\Api; use App\Models\Karyawan; use Illuminate\Http\Request; use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::all();
        return response()->json($karyawans);
    }

    public function create()
    {
        // Return the view to create a new karyawan record
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'email' => 'required|email|unique:karyawans',
            'date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
        ]);

        $karyawan = Karyawan::create($request->all());
        return response()->json($karyawan);
    }

    public function show(Karyawan $karyawan)
    {
        return response()->json($karyawan);
    }

    public function edit(Karyawan $karyawan)
    {
        // Return the view to edit an existing karyawan record
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
        ]);

        $karyawan->update($request->all());
        return response()->json($karyawan);
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return response()->json(['message' => 'Karyawan record deleted successfully']);
    }
}
