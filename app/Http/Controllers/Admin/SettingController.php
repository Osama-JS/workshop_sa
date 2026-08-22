<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function index()
    {
        $allSettings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('allSettings'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');

        // Handle file uploads (Logo, Favicon)
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path, 'identity', 'image');
        }

        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path, 'identity', 'image');
        }

        // Handle text/textarea/color/boolean fields
        $data = $request->except(['_token', '_method', 'group', 'site_logo', 'site_favicon']);

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => $group,
                    'type' => 'text',
                ]);
            }
        }

        Cache::forget('site_settings');

        return back()->with('success', __('admin.settings_updated') ?? 'تم حفظ الإعدادات بنجاح.');
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $recipient = $request->input('test_email');
        $result = $this->mailService->sendTestEmail($recipient);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 422);
    }
}
