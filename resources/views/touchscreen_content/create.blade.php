@extends('layout.app')

@section('title')
    Add Touchtable Content
@endsection

@section('header_scripts')
    <script src="{{ asset('assets/global_assets/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/global_assets/js/demo_pages/editor_ckeditor_material.js') }}"></script>
    <script src="{{ asset('assets/global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Add Touchtable Content</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('touchtable.content.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Language</label>
                            <select id="lang" class="form-control" name="lang" required>
                                <option value="">Select Language</option>
                                <option value="ar">Arabic</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Enter Content Title">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Screen:</label>
                            <select name="screen_id" id="screen_id" class="form-control">
                                <option value="">Select Screen</option>
                                @foreach ($screens as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Menu</label>
                            <select name="menu_id" id="menu_id" class="form-control" required>
                                <option value="">Select Menu *</option>
                            </select>
                        </div>
                    </div>
                    <div id="background_color" class="col-md-6">
                        <div class="form-group">
                            <label for="background_color">Background Color:</label>
                            <input name="background_color" id="background_color" class="form-control"
                                   type="color" value="#ffffff">
                            @error('background_color')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div id="text_color" class="col-md-6">
                        <div class="form-group">
                            <label for="text_color">Text Color:</label>
                            <input name="text_color" id="text_color" class="form-control" type="color"
                                   value="#000000">
                            @error('text_color')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="text_bg_image">Text Background image:</label>
                            <input name="text_bg_image" id="text_bg_image" class="form-control" type="file">
                            @error('text_bg_image')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Content:</label>
                            <textarea name="content" id="editor-full1" rows="4" cols="4" required></textarea>
                            @error('content')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <ul id="file-upload-list2" class="list-unstyled">
                    </ul>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Add <i class="icon-add ml-2"></i></button>
                </div>
            </form>
            <form action="{{ route('touchtable.media.upload') }}" class="dropzone mt-3" id="dropzone_multiple">
            </form>

            <ul id="file-upload-list" class="list-unstyled">
            </ul>

        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        var list = $('#file-upload-list');
        var list2 = $('#file-upload-list2');
        console.log(list)
        // Multiple files
        Dropzone.options.dropzoneMultiple = {
            paramName: "media", // The name that will be used to transfer the file
            dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
            maxFilesize: 204800, // MB
            addRemoveLinks: true,
            chunking: false,
            chunkSize: 2000000,
            // If true, the individual chunks of a file are being uploaded simultaneously.
            parallelChunkUploads: true,
            acceptedFiles: 'video/*, image/*',
            init: function() {
                this.on('addedfile', function() {
                        list.append('<li>Uploading</li>')
                    }),
                    this.on('sending', function(file, xhr, formData) {
                        formData.append("_token", "{{ csrf_token() }}");

                        // This will track all request so we can get the correct request that returns final response:
                        // We will change the load callback but we need to ensure that we will call original
                        // load callback from dropzone
                        var dropzoneOnLoad = xhr.onload;
                        xhr.onload = function(e) {
                            dropzoneOnLoad(e)
                            // Check for final chunk and get the response
                            var uploadResponse = JSON.parse(xhr.responseText)
                            if (typeof uploadResponse.name === 'string') {
                                list.append('<li>Uploaded: ' + uploadResponse.path + uploadResponse.name +
                                    '</li>')
                                list2.append('<input type="hidden" name="file_names[]" value="' +
                                    uploadResponse.name +
                                    '" ><input type="hidden" name="types[]" value="' +
                                    uploadResponse.type + '" >')
                            }
                        }
                    })
            }
        };


        $('#screen_id').change(function () {
            screen_id = this.value | 0
            var url = "../getscreensidemenu/" + screen_id
            console.log(url)
            $.ajax({
                url: url,
                method: 'get',
                dataType: 'json',
                success: function (response) {
                    listScreenMenu = response
                    var html_text = '<option value="">Select Menu *</option>'
                    if (response.length) {
                        for (var i = 0; i < response.length; i++) {
                            html_text += '<option value="' + response[i].id + '">' + response[i].name +
                                '</option>'
                        }
                    }
                    $('#menu_id').empty().append(html_text);
                }
            })
        });
        $('#menu_id').change(function () {
            let menu = listScreenMenu.find(l => l.id === parseInt($('#menu_id').val()))
            $('#menu_level').val(menu.level)
        });
    </script>
@endsection
