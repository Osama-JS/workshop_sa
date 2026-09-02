<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioAttachment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $query = Portfolio::with(['service', 'attachments']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $portfolios = $query->orderBy('sort_order')->latest()->paginate(12)->withQueryString();
        $services = Service::all();

        return view('admin.portfolios.index', compact('portfolios', 'services'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.portfolios.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:portfolios,slug'],
            'service_id' => ['nullable', 'exists:services,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'completion_date' => ['nullable', 'date'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'pdf_documents.*' => ['nullable', 'mimes:pdf', 'max:10240'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['title_en']);

        $originalSlug = $slug;
        $count = 1;
        while (Portfolio::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'slug' => $slug,
            'service_id' => $validated['service_id'] ?? null,
            'client_name' => $validated['client_name'] ?? null,
            'location' => $validated['location'] ?? null,
            'completion_date' => $validated['completion_date'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('portfolios', 'public');
            $data['main_image'] = $path;
        }

        $portfolio = Portfolio::create($data);

        // Upload Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $imgPath = $img->store('portfolios/gallery', 'public');
                PortfolioAttachment::create([
                    'portfolio_id' => $portfolio->id,
                    'file_path' => $imgPath,
                    'file_name' => $img->getClientOriginalName(),
                    'media_type' => 'image',
                    'file_size' => round($img->getSize() / 1024) . ' KB',
                ]);
            }
        }

        // Upload PDF Documents / Catalogs
        if ($request->hasFile('pdf_documents')) {
            foreach ($request->file('pdf_documents') as $pdf) {
                $pdfPath = $pdf->store('portfolios/docs', 'public');
                PortfolioAttachment::create([
                    'portfolio_id' => $portfolio->id,
                    'file_path' => $pdfPath,
                    'file_name' => $pdf->getClientOriginalName(),
                    'media_type' => 'pdf',
                    'file_size' => round($pdf->getSize() / 1024) . ' KB',
                ]);
            }
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'تمت إضافة المشروع لمعرض الأعمال بنجاح.');
    }

    public function edit(Portfolio $portfolio)
    {
        $services = Service::all();
        $portfolio->load('attachments');
        return view('admin.portfolios.edit', compact('portfolio', 'services'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:portfolios,slug,' . $portfolio->id],
            'service_id' => ['nullable', 'exists:services,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'completion_date' => ['nullable', 'date'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'pdf_documents.*' => ['nullable', 'mimes:pdf', 'max:10240'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'service_id' => $validated['service_id'] ?? null,
            'client_name' => $validated['client_name'] ?? null,
            'location' => $validated['location'] ?? null,
            'completion_date' => $validated['completion_date'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if (!empty($validated['slug']) && $validated['slug'] !== $portfolio->slug) {
            $data['slug'] = Str::slug($validated['slug']);
        }

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('portfolios', 'public');
            $data['main_image'] = $path;
        }

        $portfolio->update($data);

        // Upload new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $imgPath = $img->store('portfolios/gallery', 'public');
                PortfolioAttachment::create([
                    'portfolio_id' => $portfolio->id,
                    'file_path' => $imgPath,
                    'file_name' => $img->getClientOriginalName(),
                    'media_type' => 'image',
                    'file_size' => round($img->getSize() / 1024) . ' KB',
                ]);
            }
        }

        // Upload new PDFs
        if ($request->hasFile('pdf_documents')) {
            foreach ($request->file('pdf_documents') as $pdf) {
                $pdfPath = $pdf->store('portfolios/docs', 'public');
                PortfolioAttachment::create([
                    'portfolio_id' => $portfolio->id,
                    'file_path' => $pdfPath,
                    'file_name' => $pdf->getClientOriginalName(),
                    'media_type' => 'pdf',
                    'file_size' => round($pdf->getSize() / 1024) . ' KB',
                ]);
            }
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'تم تحديث المشروع وملفاته بنجاح.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return redirect()->route('admin.portfolios.index')->with('success', 'تم حذف المشروع بنجاح.');
    }

    public function deleteAttachment(PortfolioAttachment $attachment)
    {
        $attachment->delete();
        return back()->with('success', 'تم حذف المرفق بنجاح.');
    }
}
