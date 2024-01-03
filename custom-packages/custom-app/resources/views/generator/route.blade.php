/* Auto-generated admin routes */
Route::middleware('custom-app-middlewares')->prefix('{{$baseUrl}}')->name('custom-app.')->group(function () {
    Route::get('{{$routeName}}', [{{$fullNameControllerClass}}, 'index'])->name('{{$routeName}}.index');
    Route::get('{{$routeName}}/create', [{{$fullNameControllerClass}}, 'create'])->name('{{$routeName}}.create');
    Route::post('{{$routeName}}', [{{$fullNameControllerClass}}, 'store'])->name('{{$routeName}}.store');
@if($export)
    Route::get('{{$routeName}}/export', [{{$fullNameControllerClass}}, 'export'])->name('{{$routeName}}.export');
@endif
    Route::get('{{$routeName}}/edit/{{$routeVariable}}', [{{$fullNameControllerClass}}, 'edit'])->name('{{$routeName}}.edit');
    Route::match(['put', 'patch'], '{{$routeName}}/{{$routeVariable}}', [{{$fullNameControllerClass}}, 'update'])->name('{{$routeName}}.update');
    Route::delete('{{$routeName}}/{{$routeVariable}}', [{{$fullNameControllerClass}}, 'destroy'])->name('{{$routeName}}.destroy');
    Route::post('{{$routeName}}/bulk-destroy', [{{$fullNameControllerClass}}, 'bulkDestroy'])->name('{{$routeName}}.bulk-destroy');
});
