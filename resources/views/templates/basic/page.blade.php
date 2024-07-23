@extends($activeTemplate.'layouts.frontend')

@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center mb-none-30">
                @php
                    echo $content->data_values->content
                @endphp
            </div><!-- row end -->
        </div>
    </section>
@endsection
