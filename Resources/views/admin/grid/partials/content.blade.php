<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ trans('media::media.file picker') }}</title>
    {!! Theme::style('plugins/bootstrap/dist/css/bootstrap.min.css') !!}
    {!! Theme::style('css/main.css') !!}
    {!! Theme::style('plugins/datatables/dataTables.bootstrap4.css') !!}
    <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet" type="text/css" />
    <link href="{!! Module::asset('media:css/dropzone.css') !!}" rel="stylesheet" type="text/css" />
    <style>
        body {
            background: #ecf0f5;
            margin-top: 20px;
        }
        .dropzone {
            border: 1px dashed #CCC;
            min-height: 227px;
            margin-bottom: 20px;
            display: none;
        }
    </style>
    <script>
        AuthorizationHeaderValue = 'Bearer {{ $currentUser->getFirstApiKey() }}';
    </script>
    @include('partials.asgard-globals')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <form method="POST" class="dropzone">
                {!! Form::token() !!}
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ trans('media::media.choose file') }}</h3>
                <div class="box-tools float-right">
                    <button class="btn btn-box-tool jsShowUploadForm" data-toggle="tooltip" title="" data-original-title="Upload new">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Subir imagen
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="data-table table table-bordered table-hover jsFileList data-table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>{{ trans('core::core.table.thumbnail') }}</th>
                        <th>{{ trans('media::media.table.filename') }}</th>
                        <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($files): ?>
                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td>{{ $file->id }}</td>
                            <td>
                                <?php if ($file->isImage()): ?>
                                <img src="{{ Imagy::getThumbnail($file->path, 'smallThumb') }}" alt=""/>
                                <?php else: ?>
                                <i class="fas {{ FileHelper::getFaIcon($file->media_type) }}" style="font-size: 20px;"></i>
                                <?php endif; ?>
                            </td>
                            <td>{{ $file->filename }}</td>
                            <td>
                                <div class="btn-group">
                                    <?php if ($isWysiwyg === true): ?>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        {{ trans('media::media.insert') }} <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php foreach ($thumbnails as $thumbnail): ?>
                                        <li data-file-path="{{ Imagy::getThumbnail($file->path, $thumbnail->name()) }}"
                                            data-id="{{ $file->id }}" data-media-type="{{ $file->media_type }}"
                                            data-mimetype="{{ $file->mimetype }}" class="jsInsertImage">
                                            <a href="">{{ $thumbnail->name() }} ({{ $thumbnail->size() }})</a>
                                        </li>
                                        <?php endforeach; ?>
                                        <li class="divider"></li>
                                        <li data-file-path="{{ $file->path }}" data-id="{{ $file->id }}"
                                            data-media-type="{{ $file->media_type }}" data-mimetype="{{ $file->mimetype }}" class="jsInsertImage">
                                            <a href="">Original</a>
                                        </li>
                                    </ul>
                                    <?php else: ?>
                                    <a href="" class="btn btn-primary jsInsertImage" data-id="{{ $file->id }}"
                                       data-file-path="{{ Imagy::getThumbnail($file->path, 'mediumThumb') }}"
                                       data-media-type="{{ $file->media_type }}" data-mimetype="{{ $file->mimetype }}">
                                        {{ trans('media::media.insert') }}
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
{!! Theme::script('js/jquery.min.js') !!}
{!! Theme::script('plugins/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Theme::script('plugins/datatables/jquery.dataTables.min.js') !!}
{!! Theme::script('plugins/datatables/dataTables.bootstrap4.js') !!}
<script src="{!! Module::asset('media:js/dropzone.js') !!}"></script>
<?php $config = config('asgard.media.config'); ?>
<script>
    var maxFilesize = '<?php echo $config['max-file-size'] ?>',
        acceptedFiles = '<?php echo $config['allowed-types'] ?>';
</script>
<script src="{!! Module::asset('media:js/init-dropzone.js') !!}"></script>
<script>
    $( document ).ready(function() {
        $('.jsShowUploadForm').on('click',function (event) {
            event.preventDefault();
            $('.dropzone').fadeToggle();
        });
    });
</script>

<?php $locale = App::getLocale(); ?>
<script type="text/javascript">
    $(function () {
        $('.data-table').dataTable({
            "paginate": true,
            "lengthChange": true,
            "filter": true,
            "sort": true,
            "info": true,
            "autoWidth": true,
            "order": [[ 0, "desc" ]],
            "language": {
                "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
            }
        });
    });
</script>
