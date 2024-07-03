<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use App\Models\Hobby;
use App\Models\Family;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;


class FamilyController extends Controller
{
    public function create()
    {
        $states = State::all();
        return view('family.create', compact('states'));
    }

    public function store(Request $request)
    {
        Log::info('Request received in store method');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birthdate' => 'required|date|before:-21 years',
            'mobile_no' => 'required|string|max:15',
            'address' => 'required|string',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|digits_between:6,10',
            'marital_status' => 'required|string|in:Married,Unmarried',
            'wedding_date' => 'nullable|required_if:marital_status,Married|date',
            'hobbies' => 'nullable|array',
            'hobbies.*' => 'nullable|string',
            'photo' => 'nullable|max:2048'
        ]);

        Log::info('Validation successful', ['validatedData' => $validatedData]);

        $validatedData['photo'] = $this->handleFileUpload($request, 'photo', 'photos');

        if ($request->has('hobbies')) {
            $validatedData['hobbies'] = json_encode($request->hobbies);
        }

        $family = Family::create($validatedData);

        if ($request->has('family_members')) {
            foreach ($request->family_members as $index => $memberData) {
                $validatedMemberData = Validator::make($memberData, [
                    'name' => 'required|string|max:255',
                    'birthdate' => 'required|date',
                    'marital_status' => 'required|string|in:Married,Unmarried',
                    'wedding_date' => 'nullable|required_if:marital_status,Married|date',
                    'education' => 'required|string|max:255',
                    'photo' => 'nullable|max:2048'
                ])->validate();

                $validatedMemberData['photo'] = $this->handleNestedFileUpload($request, "family_members.$index.photo", 'photos');

                $family->members()->create($validatedMemberData);
            }
        }

        return redirect()->route('family.index')->with('success', 'Family information saved successfully!');
    }


    private function handleFileUpload(Request $request, $fieldName, $folderName)
    {
        if ($request->hasFile($fieldName)) {
            return $request->file($fieldName)->store($folderName, 'public');
        }
        return null;
    }

    private function handleNestedFileUpload(Request $request, $fieldPath, $folderName)
    {
        $fieldParts = explode('.', $fieldPath);
        $file = $request;

        foreach ($fieldParts as $part) {
            if (isset($file[$part])) {
                $file = $file[$part];
            } else {
                return null;
            }
        }

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            return $file->store($folderName, 'public');
        }
        return null;
    }

    public function store_read_bckp(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birthdate' => 'required|date|before:-21 years',
            'mobile_no' => 'required|string|max:15',
            'address' => 'required|string',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|max:10',
            'marital_status' => 'required|string|in:Married,Unmarried',
            'wedding_date' => 'nullable|required_if:marital_status,Married|date',
            'hobbies' => 'nullable|array',
            'hobbies.*' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('photos', 'public');
        }


        if ($request->has('hobbies')) {
            $validatedData['hobbies'] = json_encode($request->hobbies);
        }
        dd($validatedData);

        //$family = Family::create($validatedData);
        //dd($family);
        if ($request->has('family_members')) {
            foreach ($request->family_members as $memberData) {
                $validatedMemberData = Validator::make($memberData, [
                    'name' => 'required|string|max:255',
                    'birthdate' => 'required|date',
                    'marital_status' => 'required|string|in:Married,Unmarried',
                    'wedding_date' => 'nullable|required_if:marital_status,Married|date',
                    'education' => 'required|string|max:255',
                    'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                ])->validate();


                if (isset($memberData['photo']) && $request->hasFile('family_members.' . $memberData['photo'])) {
                    $validatedMemberData['photo'] = $request->file('family_members.' . $memberData['photo'])->store('photos', 'public');
                }


                $family->members()->create($validatedMemberData);
            }
        }


        /* return redirect()->route('family.index')->with('success', 'Family information saved successfully!');*/
    }

    public function index()
    {
        $families = Family::withCount('members')->get();
        return view('family.index', compact('families'));
    }

    public function show($id)
    {
        $family = Family::with('members', 'hobbies')->findOrFail($id);
        return view('family.show', compact('family'));
    }

}
