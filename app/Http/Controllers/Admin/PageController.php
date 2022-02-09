<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Pages;
use App\Repositories\Interfaces\PagesRepositoryInterface;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PageController extends Controller
{
    use ResetsPasswords;
    private $pageRepository;

    public function __construct(PagesRepositoryInterface $pagesRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->pageRepository = $pagesRepository;
    }

    /**
     * page edit form
     */
    public function edit()
    {
        $page = Pages::all();
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * update page record
     */
    public function update(UpdatePageRequest $request, Pages $pages)
    {
        $postData = $request->all();
        $updateRecord = [
            'content' => $postData['content'],
        ];
        $this->pageRepository->update($postData['id'], $updateRecord);
        return redirect('page/edit')->with('success', 'Page Content has been updated successfully!');
    }


}
