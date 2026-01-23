<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>
<?php require_once __DIR__ . '/companyHeader.view.php'; ?>

    <main class="main-content">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Applications Overview -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Job Applications</h2>
                <p class="card-subtitle">Manage applications for your job postings</p>
            </div>
            
            <!-- Filter Options -->
            <div class="filter-section" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <input type="text" id="applicantSearch" class="form-control" placeholder="Search by name or email..." style="max-width: 300px;">
                
                <select id="positionFilter" class="filter-select form-control" style="max-width: 200px;">
                    <option value="">All Positions</option>
                    <?php if (!empty($data['jobs'])): ?>
                        <?php foreach ($data['jobs'] as $job): ?>
                            <option value="<?= htmlspecialchars($job['title']) ?>">
                                <?= htmlspecialchars($job['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                
                <select id="applicationStatusFilter" class="filter-select form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="interviewed">Interviewed</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <button id="resetAppFilters" class="btn btn-secondary" style="margin-left: auto;">Reset Filters</button>
            </div>

            <!-- Applications Table -->
            <table class="table" id="applicationsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-column="applicant" data-order="asc">
                            Applicant 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="position" data-order="asc">
                            Position 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="date" data-order="asc">
                            Applied Date 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="status" data-order="asc">
                            Status 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="applicationsTableBody">
                    <?php if (!empty($data['applications'])): ?>
                        <?php foreach ($data['applications'] as $application): ?>
                            <tr data-applicant="<?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?>" 
                                data-email="<?= htmlspecialchars($application['email']) ?>" 
                                data-position="<?= htmlspecialchars($application['job_title']) ?>" 
                                data-date="<?= htmlspecialchars($application['applied_at']) ?>" 
                                data-status="<?= htmlspecialchars($application['status']) ?>">
                                <td>
                                    <div class="applicant-info">
                                        <strong><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></strong>
                                        <div class="applicant-email"><?= htmlspecialchars($application['email']) ?></div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($application['job_title']) ?></td>
                                <td><?= date('M d, Y', strtotime($application['applied_at'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= getBadgeClass($application['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-primary" onclick="viewApplication(<?= $application['application_id'] ?>)">View</button>
                                        
                                        <?php if ($application['status'] == 'pending' || $application['status'] == 'under_review'): ?>
                                            <button class="btn btn-sm btn-secondary" onclick="updateStatus(<?= $application['application_id'] ?>, 'shortlisted')">Shortlist</button>
                                        <?php endif; ?>
                                        
                                        <?php if ($application['status'] == 'shortlisted'): ?>
                                            <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $application['application_id'] ?>, 'interviewed')">Interview</button>
                                        <?php endif; ?>
                                        
                                        <?php if ($application['status'] == 'interviewed'): ?>
                                            <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $application['application_id'] ?>, 'hired')">Hire</button>
                                        <?php endif; ?>
                                        
                                        <?php if ($application['status'] != 'hired' && $application['status'] != 'rejected'): ?>
                                            <button class="btn btn-sm btn-danger" onclick="updateStatus(<?= $application['application_id'] ?>, 'rejected')">Reject</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                <p>No applications found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php
    // Helper function for badge classes
    function getBadgeClass($status) {
        switch ($status) {
            case 'pending':
                return 'warning';
            case 'under_review':
                return 'info';
            case 'shortlisted':
                return 'primary';
            case 'interviewed':
                return 'info';
            case 'hired':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
    ?>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (profileTrigger && dropdownMenu) {
                profileTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    dropdownMenu.classList.remove('active');
                });
                
                // Prevent dropdown from closing when clicking inside
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Search and Sort Functionality for Applications
            const searchInput = document.getElementById('applicantSearch');
            const positionFilter = document.getElementById('positionFilter');
            const statusFilter = document.getElementById('applicationStatusFilter');
            const resetBtn = document.getElementById('resetAppFilters');
            const tableBody = document.getElementById('applicationsTableBody');
            const sortableHeaders = document.querySelectorAll('#applicationsTable .sortable');
            
            // Check if all elements exist
            if (!searchInput || !positionFilter || !statusFilter || !resetBtn || !tableBody) {
                console.error('Some filter elements are missing');
                return;
            }
            
            // Search and filter functionality
            searchInput.addEventListener('input', filterApplications);
            positionFilter.addEventListener('change', filterApplications);
            statusFilter.addEventListener('change', filterApplications);
            
            // Reset filters
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                positionFilter.value = '';
                statusFilter.value = '';
                filterApplications();
            });
            
            function filterApplications() {
                const searchTerm = searchInput.value.toLowerCase();
                const positionValue = positionFilter.value;
                const statusValue = statusFilter.value;
                const rows = tableBody.querySelectorAll('tr');
                
                let visibleCount = 0;
                rows.forEach(row => {
                    const applicantName = row.getAttribute('data-applicant');
                    const applicantEmail = row.getAttribute('data-email');
                    const position = row.getAttribute('data-position');
                    const status = row.getAttribute('data-status');
                    
                    // Check if attributes exist
                    if (!applicantName || !applicantEmail || !position || !status) {
                        return;
                    }
                    
                    const matchesSearch = applicantName.toLowerCase().includes(searchTerm) || 
                                         applicantEmail.toLowerCase().includes(searchTerm);
                    const matchesPosition = positionValue === '' || position === positionValue;
                    const matchesStatus = statusValue === '' || status === statusValue;
                    
                    if (matchesSearch && matchesPosition && matchesStatus) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Sorting functionality
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.getAttribute('data-column');
                    const currentOrder = this.getAttribute('data-order');
                    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                    
                    // Reset all headers
                    sortableHeaders.forEach(h => {
                        h.setAttribute('data-order', 'asc');
                        h.classList.remove('sorted-asc', 'sorted-desc');
                    });
                    
                    // Set current header
                    this.setAttribute('data-order', newOrder);
                    this.classList.add(newOrder === 'asc' ? 'sorted-asc' : 'sorted-desc');
                    
                    sortApplicationsTable(column, newOrder);
                });
            });
            
            function sortApplicationsTable(column, order) {
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                
                rows.sort((a, b) => {
                    let aValue = a.getAttribute('data-' + column);
                    let bValue = b.getAttribute('data-' + column);
                    
                    // Check if values exist
                    if (!aValue || !bValue) {
                        return 0;
                    }
                    
                    // Handle date sorting
                    if (column === 'date') {
                        aValue = new Date(aValue);
                        bValue = new Date(bValue);
                        
                        if (order === 'asc') {
                            return aValue - bValue;
                        } else {
                            return bValue - aValue;
                        }
                    }
                    // Handle text sorting
                    else {
                        aValue = aValue.toLowerCase();
                        bValue = bValue.toLowerCase();
                        
                        if (order === 'asc') {
                            return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
                        } else {
                            return aValue < bValue ? 1 : aValue > bValue ? -1 : 0;
                        }
                    }
                });
                
                // Re-append sorted rows
                rows.forEach(row => tableBody.appendChild(row));
            }
        });

        // Function to view application details
        function viewApplication(applicationId) {
            window.location.href = '<?= BASE_URL ?>/company/viewapplication/' + applicationId;
        }

        // Function to update application status
        function updateStatus(applicationId, status) {
            if (!confirm(`Are you sure you want to change the status to "${status}"?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('application_id', applicationId);
            formData.append('status', status);

            fetch('<?= BASE_URL ?>/company/updateApplicationStatus', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application status updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
