<script src="{{ asset('front/assets/js/bootstrap-5.0.0-beta1.min.js') }}"></script>
<script src="{{ asset('front/assets/js/wow.min.js') }}"></script>
<script src="{{ asset('front/assets/js/tiny-slider.js') }}"></script>
<script src="{{ asset('front/assets/js/glightbox.min.js') }}"></script>
<script src="{{ asset('front/assets/js/count-up.min.js') }}"></script>
<script src="{{ asset('front/assets/js/imagesloaded.min.js') }}"></script>
<script src="{{ asset('front/assets/js/main.js') }}"></script>
<script src="{{ asset('front/assets/js/simalex.js') }}?v={{ time() }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('simalexFileTrigger');
    const input = document.getElementById('simalexAttachmentInput');
    const list = document.getElementById('simalexSelectedFiles');

    if (!trigger || !input || !list) {
        return;
    }

    const dataTransfer = new DataTransfer();
    const maxFiles = 5;
    const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    const maxSize = 5 * 1024 * 1024;

    trigger.addEventListener('click', function () {
        input.click();
    });

    input.addEventListener('change', function () {
        Array.from(input.files).forEach(function (file) {
            const extension = file.name.split('.').pop().toLowerCase();

            if (dataTransfer.files.length >= maxFiles) {
                alert('Maksimal 5 file lampiran.');
                return;
            }

            if (!allowedExtensions.includes(extension)) {
                alert('Format file tidak diizinkan: ' + file.name);
                return;
            }

            if (file.size > maxSize) {
                alert('Ukuran file maksimal 5 MB: ' + file.name);
                return;
            }

            const alreadyExists = Array.from(dataTransfer.files).some(function (existingFile) {
                return existingFile.name === file.name && existingFile.size === file.size;
            });

            if (alreadyExists) {
                alert('File sudah dipilih: ' + file.name);
                return;
            }

            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
        renderFiles();
    });

    function renderFiles() {
        list.innerHTML = '';

        Array.from(dataTransfer.files).forEach(function (file, index) {
            const fileSizeKb = (file.size / 1024).toFixed(1);

            const item = document.createElement('div');
            item.className = 'simalex-selected-file';

            item.innerHTML = `
                <div class="simalex-selected-file-info">
                    <i class="lni lni-files"></i>
                    <div>
                        <span title="${file.name}">${file.name}</span>
                        <small>${fileSizeKb} KB</small>
                    </div>
                </div>

                <button type="button" class="simalex-remove-file-btn" data-index="${index}">
                    ×
                </button>
            `;

            list.appendChild(item);
        });

        list.querySelectorAll('.simalex-remove-file-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                removeFile(Number(button.dataset.index));
            });
        });
    }

    function removeFile(removeIndex) {
        const newDataTransfer = new DataTransfer();

        Array.from(dataTransfer.files).forEach(function (file, index) {
            if (index !== removeIndex) {
                newDataTransfer.items.add(file);
            }
        });

        dataTransfer.items.clear();

        Array.from(newDataTransfer.files).forEach(function (file) {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
        renderFiles();
    }
});
</script>