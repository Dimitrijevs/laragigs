<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // show all listings
    public function index() {
        return view('listings.index', [
            'listings'=> Listing::latest()->filter(
                request(['tag', 'search'])
            )->paginate(6),
        ]);
    }

    // show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // show create listing form
    public function create() {
        return view('listings.create');
    }

    // store listing
    public function store(Request $request) {
        $formFields = $request->validate([
            'title'=>'required',
            'company'=>['required', Rule::unique('listings', 'company')],
            'location'=>'required',
            'website'=>'required',
            'email'=>['required', 'email'],
            'description'=>'required',
            'tags'=>'required',
        ]);

        // store logo information in folder storage/app/public/logos
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Your listing has been created');
    }

    //show edit form
    public function edit(Listing $listing) {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    //update listing data
    public function update(Request $request, Listing $listing) {

        //make sure logged in user is the owner of the listing
        if($listing->user_id != auth()->id()) {
            abort(403, 'You are not the owner of this listing');
        };

        $formFields = $request->validate([
            'title'=>'required',
            'company'=>'required',
            'location'=>'required',
            'website'=>'required',
            'email'=>['required', 'email'],
            'description'=>'required',
            'tags'=>'required',
        ]);

        // store logo information in folder storage/app/public/logos
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return redirect('/')->with('message', 'Your listing has been updated successfully!');
    }

    public function destroy(Listing $listing) {

        //make sure logged in user is the owner of the listing
        if($listing->user_id != auth()->id()) {
            abort(403, 'You are not the owner of this listing');
        };

        $listing->delete();

        return redirect('/')->with('message', 'Your listing has been deleted successfully!');
    }

    //manage listings
    public function manage(){
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);;
    }
}
