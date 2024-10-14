<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dropbox Integration</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Add custom styles here -->
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- Logo or Brand Name -->
        <a class="navbar-brand" href="#">Drop Box</a>

        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li> -->
            </ul>

            <!-- Right Aligned Button (e.g. Login) -->
            

        <form action="{{ route('logout') }}" method="POST" class="">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        </div>
    </div>
</nav>


    <div class="container mt-5">
        @if(auth()->user()->dropbox_token)
        <div class="alert alert-success">
            <h3 class="mb-3">Dropbox is connected!</h3>
            <h5 class="mb-3">Use the button to attach files from Dropbox</h5>

            <!-- Button to trigger file picker from Dropbox -->
            <div id="dropbox-picker"></div>

            <!-- Selected images list -->
            <div id="selected-images" class="mt-3"></div>

            <!-- Form to submit selected Dropbox images -->
            <form action="{{ route('upload.dropbox.files') }}" method="POST" id="upload-form">
                @csrf
                <input type="hidden" name="files" id="selected-files">
                <button type="submit" class="btn btn-primary mt-3" id="upload-button" disabled>Upload Selected Images</button>
            </form>

            <!-- Disconnect Dropbox Form -->
            <form action="{{ route('disconnect.dropbox') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-danger">Disconnect Dropbox</button>
            </form>
        </div>
        @else
        <a href="{{ route('connect.dropbox') }}" class="btn btn-success">Connect to Dropbox</a>
        @endif

        @if(auth()->user()->dropbox_token)
        <!-- Display any uploaded images here -->
        @if($uploads = auth()->user()->uploads)
        <h3 class="mt-3">Your Uploaded Images</h3>
        <div class="row">
            @foreach($uploads as $upload)
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ asset('storage/' . $upload->file_name) }}" class="card-img-top" alt="{{ $upload->file_name }}" style="width: 100%; height: auto;">
                    <!-- <div class="card-body">
                        <p class="card-text">{{ $upload->file_name }}</p>
                    </div> -->
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="mt-3">You haven't uploaded any images yet.</p>
        @endif
        @endif
    </div>

    <!-- Dropbox Chooser Script -->
    <script src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="<?php echo config('services.dropbox.client_id'); ?>"></script>

    <!-- Bootstrap JS (requires Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Dropbox Chooser Button Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectedFiles = [];

            var button = Dropbox.createChooseButton({
                success: function(files) {
                    selectedFiles = []; // Reset selected files
                    var fileList = document.getElementById('selected-images');
                    fileList.innerHTML = ''; // Clear previous selections

                    files.forEach(function(file) {
                        selectedFiles.push(file.link); // Save the Dropbox file link
                        // Display the file name in the frontend
                        fileList.innerHTML += `<p>${file.name}</p>`;
                    });

                    // Enable the upload button once files are selected
                    if (selectedFiles.length > 0) {
                        document.getElementById('upload-button').disabled = false;
                        document.getElementById('selected-files').value = JSON.stringify(selectedFiles);
                    }
                },
                multiselect: true,
                extensions: ['.png', '.jpg', '.jpeg']
            });

            var pickerContainer = document.getElementById('dropbox-picker');
            if (pickerContainer) {
                pickerContainer.appendChild(button);
            }
        });
    </script>
</body>
</html>
