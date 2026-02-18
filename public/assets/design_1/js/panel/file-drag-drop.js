/**
 * File Drag and Drop Functionality
 * Provides accessible drag and drop file upload with keyboard support
 */

(function($) {
    'use strict';

    class FileDragDrop {
        constructor($zone) {
            this.$zone = $zone;
            this.$fileInput = null;
            this.$overlay = $zone.find('.js-drag-drop-overlay');
            this.$content = $zone.find('.js-drag-drop-content');
            this.$selectedFileDisplay = $zone.closest('.form-group').find('.js-selected-file-display');
            this.$fileName = this.$selectedFileDisplay.find('.js-selected-file-name');
            this.$fileSize = this.$selectedFileDisplay.find('.js-selected-file-size');
            this.$removeBtn = this.$selectedFileDisplay.find('.js-remove-file');
            this.processingFile = false; // Flag to prevent infinite loops
            
            this.init();
        }

        init() {
            const fileInputId = this.$zone.data('file-input-id');
            this.$fileInput = $(`#${fileInputId}`);
            
            if (!this.$fileInput.length) {
                console.warn('File input not found:', fileInputId);
                return;
            }

            this.bindEvents();
            this.bindOverlayEvents();
        }

        bindEvents() {
            // Drag and drop events
            this.$zone.on('dragover', (e) => this.handleDragOver(e));
            this.$zone.on('dragleave', (e) => this.handleDragLeave(e));
            this.$zone.on('drop', (e) => this.handleDrop(e));
            
            // Click to upload - allow clicks anywhere on the zone
            this.$zone.on('click', (e) => {
                // Don't trigger if clicking the remove file button
                if ($(e.target).closest('.js-remove-file').length) {
                    return;
                }
                
                // Allow clicks anywhere on the zone (including overlay)
                e.preventDefault();
                e.stopPropagation();
                
                // Trigger file input click
                if (this.$fileInput.length) {
                    this.$fileInput[0].click();
                }
            });

            // Keyboard support (Enter/Space to activate)
            this.$zone.on('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.$fileInput.trigger('click');
                }
            });

            // File input change - use namespaced event to allow temporary removal
            this.$fileInput.on('change.fileDragDrop', (e) => this.handleFileSelect(e));

            // Remove file button
            this.$removeBtn.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.removeFile();
            });

            // Update custom file label when file is selected
            this.$fileInput.on('change', () => {
                const fileName = this.$fileInput[0].files[0]?.name || '';
                if (fileName) {
                    this.$fileInput.closest('.custom-file').find('.custom-file-label').text(fileName);
                }
            });
        }

        handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            this.$zone.addClass('drag-over');
            this.$overlay.removeClass('d-none');
        }

        handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Only remove overlay if we're leaving the zone itself
            if (!this.$zone[0].contains(e.relatedTarget)) {
                this.$zone.removeClass('drag-over');
                this.$overlay.addClass('d-none');
            }
        }

        handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            
            this.$zone.removeClass('drag-over');
            this.$overlay.addClass('d-none');

            const files = e.originalEvent.dataTransfer?.files;
            if (files && files.length > 0) {
                this.processFiles(files);
            }
        }
        
        // Also handle drop on overlay (in case pointer-events doesn't work in some browsers)
        bindOverlayEvents() {
            this.$overlay.on('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleDrop(e);
            });
            
            this.$overlay.on('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleDragOver(e);
            });
            
            this.$overlay.on('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleDragLeave(e);
            });
        }

        handleFileSelect(e) {
            // Prevent recursive calls
            if (this.processingFile) {
                return;
            }
            
            const files = e.target.files;
            if (files && files.length > 0) {
                this.processFiles(files);
            }
        }

        processFiles(files) {
            // Prevent recursive calls
            if (this.processingFile) {
                return;
            }
            
            this.processingFile = true;
            
            try {
                const file = files[0];
                
                // Validate file size (2GB = 2147483648 bytes)
                const maxSize = 2147483648;
                if (file.size > maxSize) {
                    this.showError('File size exceeds 2GB limit');
                    this.processingFile = false;
                    return;
                }

                // Validate file type
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const allowedExtensions = ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'pdf', 'doc', 'docx', 'txt'];
                
                if (!allowedExtensions.includes(fileExtension)) {
                    this.showError('File type not supported. Please upload MP4, AVI, MKV, MOV, PDF, DOC, DOCX, or TXT files.');
                    this.processingFile = false;
                    return;
                }

                // Set file to input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$fileInput[0].files = dataTransfer.files;

                // Update display
                this.displaySelectedFile(file);

                // Trigger change event for any listeners (but prevent our handler from processing again)
                // Temporarily remove our handler, trigger, then re-add it
                this.$fileInput.off('change.fileDragDrop');
                this.$fileInput.trigger('change');
                
                // Re-attach handler after a short delay
                setTimeout(() => {
                    this.$fileInput.on('change.fileDragDrop', (e) => this.handleFileSelect(e));
                    this.processingFile = false;
                }, 100);
            } catch (error) {
                console.error('Error processing file:', error);
                this.showError('Error processing file. Please try again.');
                this.processingFile = false;
            }
        }

        displaySelectedFile(file) {
            const fileName = file.name;
            const fileSize = this.formatFileSize(file.size);
            
            this.$fileName.text(fileName);
            this.$fileSize.text(fileSize);
            this.$selectedFileDisplay.removeClass('d-none');
            
            // Update custom file label
            const $customLabel = this.$fileInput.closest('.custom-file').find('.custom-file-label');
            if ($customLabel.length) {
                $customLabel.text(fileName);
            }
        }

        removeFile() {
            // Clear file input
            this.$fileInput.val('');
            
            // Clear custom file label
            const $customLabel = this.$fileInput.closest('.custom-file').find('.custom-file-label');
            if ($customLabel.length) {
                $customLabel.text($customLabel.data('original-text') || 'Browse');
            }
            
            // Hide selected file display
            this.$selectedFileDisplay.addClass('d-none');
            
            // Trigger change event
            this.$fileInput.trigger('change');
        }

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        showError(message) {
            // Remove any existing error messages
            this.$zone.closest('.form-group').find('.invalid-feedback').remove();
            
            // Show error message
            const $error = $('<div class="invalid-feedback d-block">' + message + '</div>');
            this.$zone.closest('.form-group').append($error);
            
            // Remove error after 5 seconds
            setTimeout(() => {
                $error.fadeOut(() => $error.remove());
            }, 5000);
        }
    }

    // Make FileDragDrop available globally
    window.FileDragDrop = FileDragDrop;

    // Initialize on document ready
    $(document).ready(function() {
        // Initialize for all drag drop zones
        $('.js-file-drag-drop-zone').each(function() {
            if (!$(this).data('drag-drop-initialized')) {
                new FileDragDrop($(this));
                $(this).data('drag-drop-initialized', true);
            }
        });

        // Re-initialize when new zones are added dynamically (using MutationObserver for better performance)
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const $node = $(node);
                            const $zones = $node.find('.js-file-drag-drop-zone').addBack('.js-file-drag-drop-zone');
                            $zones.each(function() {
                                if (!$(this).data('drag-drop-initialized')) {
                                    new FileDragDrop($(this));
                                    $(this).data('drag-drop-initialized', true);
                                }
                            });
                        }
                    });
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    });

    // Also initialize when storage type changes (for dynamic forms)
    $(document).on('change', '.js-file-storage', function() {
        const $form = $(this).closest('form, .js-content-form');
        const $uploadZone = $form.find('.js-file-drag-drop-zone');
        
        if ($uploadZone.length && !$uploadZone.data('drag-drop-initialized')) {
            new FileDragDrop($uploadZone);
            $uploadZone.data('drag-drop-initialized', true);
        }
    });

})(jQuery);

