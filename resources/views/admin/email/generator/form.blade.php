@section('meta_title', '- ' . $cardTitle)
<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>DeveloPro {{ $cardTitle }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">

    <meta name="csrf-token" content="{{ csrf_token() }}" />



</head>

<body class="lang-pl">
    <div id="root"></div>

    <script>
        window.email = window.email || {};
        window.email.generator = window.email.generator || {};

        window.email.generator.offerLink = 'link do oferty'
        window.email.generator.templateJSON = @json($template_json);
        window.email.generator.csrf = '{{ csrf_token() }}';
        window.email.generator.templateID = '{{ $id }}';
        window.email.generator.allFilesURL = '{{ route('files.index') }}';
        window.email.generator.searchFileURL = '{{ route('files.search') }}';
        window.email.generator.clientPanelLink = '{{ route('front.login') }}';
        window.email.generator.preview = '{{ route('front.email-template-preview.show', $id) }}';
        window.email.generator.backURL = '{{ route('admin.email.generator.index') }}';
        window.email.generator.updateTemplate = '{{ route('admin.email.generator.update-template') }}';
        window.email.generator.templateType = '{{ $template_type }}';
    </script>


</body>
<script src="{{ asset('js/template-generator.js') }}" type="module"></script>

</html>
