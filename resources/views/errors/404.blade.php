@extends('layouts.error_layout')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="error-page d-flex flex-column">
        <h1 class="headline text-danger">404</h1>
        <div class="error-content ml-0">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Page not found.</h3>
            <p>
                We could not find the page you were looking for.
                Meanwhile, you may return to dashboard or try using the go back button.
            </p>
            <a href="#" name="submit" class="btn btn-danger" onclick="return window.history.back(-1)">
                <i class="fas fa-backward"></i> Go Back
            </a>
        </div>
    </div>
</div>
@endsection