    </main>
    
    <!-- Footer -->
    <footer class="mt-5 py-4" style="background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%); border-top: 1px solid #e3e6f0;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        <i class="bi bi-heart-fill text-danger"></i> 
                        &copy; 2024 Kovil App. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0 text-muted">
                        <i class="bi bi-lightning-fill text-warning"></i>
                        Modern Version - Built with Bootstrap 5
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables with modern styling
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: '<"row align-items-center"<"col-sm-12 col-md-7 d-flex align-items-center gap-3"l i><"col-sm-12 col-md-5 d-flex justify-content-end"p>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row align-items-center"<"col-sm-12 col-md-7 d-flex align-items-center gap-3"l i><"col-sm-12 col-md-5 d-flex justify-content-end"p>>',
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
            });
            
            // Initialize Select2 with modern theme
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Add smooth scrolling to all links
            $('a[href*="#"]').on('click', function(e) {
                if (this.hash !== '') {
                    e.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 100
                    }, 800);
                }
            });
            
            // Add loading animation to buttons
            $('.btn').on('click', function() {
                if (!$(this).hasClass('btn-secondary') && !$(this).hasClass('btn-link')) {
                    $(this).append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
                    setTimeout(() => {
                        $(this).find('.spinner-border').remove();
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html> 