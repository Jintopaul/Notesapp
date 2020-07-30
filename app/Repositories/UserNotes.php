<?php

namespace App\Repositories;

use App\Note;
use Illuminate\Support\Facades\Storage;



/**
 * 
 */
class UserNotes
{
	public function index($request)
	{
        $user_id = $request->user()->id;

		$notes = Note::where('user_id',$user_id)->get();

		return $notes;
	}

	public function store($request)
	{
        $user_id = $request->user()->id;

        $note = new Note();
 		$note->user_id = $user_id;
 		$note->text = $request->text;

 		if($note->save())
 		{
 			if( $request->hasFile('file'))
            {
            	$file = $request->file('file');
			
                Storage::makeDirectory('/notes/files');
                $file_name = $note->id.'_'.$file->getClientOriginalName();
                $request->file('file')->storeAs('/notes/files/',$file_name);

                $note->file = $file_name;
                $note->save();
            }

            return true;
 		}
 		else
 		{
            return false;
 		}
	}

	public function note($request, $id)
	{
        $user_id = $request->user()->id;

		$note = Note::where('id',$id)->where('user_id',$user_id)->first();

		return $note;
	}

	public function update($request, $id)
	{
        $user_id = $request->user()->id;

        $note = Note::where('id',$id)->where('user_id',$user_id)->first();

        if($note)
        {
	 		$note->text = $request->text;

	 		if($note->save())
	 		{
	 			// if( $request->hasFile('file'))
	    //         {
	    //         	$file = $request->file('file');
				
	    //             Storage::makeDirectory('/notes/files');
	    //             $file_name = $note->id.'_'.$file->getClientOriginalName();
	    //             $request->file('file')->storeAs('/notes/files/',$file_name);

	    //             $note->file = $file_name;
	    //             $note->save();
	    //         }

	            return true;
	 		}
	 		else
	 		{
	            return false;
	 		}
        }
        else
        {
	        return false;
        }
	}

	public function destroy($request, $id)
	{
        $user_id = $request->user()->id;

        $note = Note::where('id', $id)->where('user_id', $user_id)->first();

        if($note)
        {
            if (Storage::exists('notes/files/'.$note->file)) 
            {
			    Storage::delete('notes/files/'.$note->file);
			}

        	$note->delete();

        	return true;
        }
        else
        {
        	return false;
        }
	}
}