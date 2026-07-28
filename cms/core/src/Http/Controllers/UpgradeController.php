<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Services\UpgradeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UpgradeController extends Controller
{
    protected UpgradeService $upgradeService;

    public function __construct(UpgradeService $upgradeService)
    {
        $this->upgradeService = $upgradeService;
    }

    public function index()
    {
        $info = $this->upgradeService->checkVersion();
        return view('cms-core::settings.phase9.updates', compact('info'));
    }

    public function upgrade(Request $request)
    {
        $result = $this->upgradeService->performUpgrade();

        if ($result['success']) {
            return redirect()->route('cms.updates.index')->with('success', $result['message']);
        }

        return redirect()->route('cms.updates.index')->with('error', $result['message']);
    }
}
