<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MembersBulkImport;
use App\Exports\BulkImportFailuresExport;
use App\Models\OnBehalf;
use App\Models\Package;
use App\Models\MaritalStatus;
use App\Models\MemberLanguage;
use App\Models\Caste;
use App\Models\SatsangOption;
use App\Models\DegreeLevel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;
use Excel;

class MemberBulkAddController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:bulk_member_add'])->only('index');
    }

    public function index()
    {
        return view('admin.members.bulk_member.index');
    }

    public function pdf_download_on_behalf()
    {
        $on_behalves = OnBehalf::all();

        return PDF::loadView('admin.members.bulk_member.on_behalf_download',[
            'on_behalves' => $on_behalves,
        ], [], [])->download('on-behalf.pdf');
    }

    public function pdf_download_package()
    {
        $packages = Package::where('active', 1)->get();

        return PDF::loadView('admin.members.bulk_member.package_download',[
            'packages' => $packages,
        ], [], [])->download('packages.pdf');
    }

    /**
     * Combined reference sheet for every small lookup value the bulk
     * import spreadsheet expects by name (On Behalf/Package already have
     * their own dedicated downloads above, kept for backwards compatibility).
     */
    public function reference_lists_download()
    {
        $maritalStatuses = MaritalStatus::all();
        $memberLanguages = MemberLanguage::all();
        $castes          = Caste::orderBy('name')->get();
        $degreeLevels    = DegreeLevel::orderBy('sort_order')->orderBy('name')->get();
        $satsangGroups   = [
            'Follower of (Sect)'          => SatsangOption::category('sect')->get(),
            'Nitya Pooja Daily'           => SatsangOption::category('nitya_pooja')->get(),
            'Wear Kanthi'                 => SatsangOption::category('kanthi')->get(),
            'Eat Onion / Garlic'          => SatsangOption::category('onion_garlic')->get(),
            'Perform Aarti / Ghar Sabha'  => SatsangOption::category('aarti')->get(),
            'Observe Sampradaya Fasts'    => SatsangOption::category('fasts')->get(),
            'Temple Visit Frequency'      => SatsangOption::category('temple_frequency')->get(),
        ];

        return PDF::loadView('admin.members.bulk_member.reference_lists_download', [
            'maritalStatuses' => $maritalStatuses,
            'memberLanguages' => $memberLanguages,
            'castes'          => $castes,
            'degreeLevels'    => $degreeLevels,
            'satsangGroups'   => $satsangGroups,
        ], [], [])->download('reference-lists.pdf');
    }

    public function bulk_upload(Request $request)
    {
        if (!$request->hasFile('bulk_file')) {
            flash(translate('Please choose a file to upload'))->error();
            return back();
        }

        $import = new MembersBulkImport;
        Excel::import($import, $request->file('bulk_file'));

        $successCount = $import->successCount();
        $failures     = $import->failures();

        if (empty($failures)) {
            flash(translate('All') . ' ' . $successCount . ' ' . translate('profiles imported successfully.'))->success();
            return back();
        }

        $batchId = (string) Str::uuid();
        Storage::disk('local')->put(
            "bulk_import_failures/{$batchId}.json",
            json_encode($failures)
        );

        flash(
            $successCount . ' ' . translate('of') . ' ' . ($successCount + count($failures)) . ' '
            . translate('profiles imported successfully.') . ' ' . count($failures) . ' '
            . translate('failed -- download the error report below to see why, fix those rows, and re-upload just them.')
        )->error();

        return back()->with('bulk_import_failure_batch', $batchId);
    }

    public function download_failures(string $batchId)
    {
        $path = "bulk_import_failures/{$batchId}.json";
        if (!Storage::disk('local')->exists($path)) {
            flash(translate('That error report has expired or was already downloaded.'))->error();
            return back();
        }

        $failures = json_decode(Storage::disk('local')->get($path), true) ?? [];

        return Excel::download(new BulkImportFailuresExport($failures), 'bulk-import-errors.xlsx');
    }
}
