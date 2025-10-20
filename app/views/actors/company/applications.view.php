<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
</head>
<body>
    <header class="company-header">
        <a href="<?= BASE_URL ?>/company/landing" class="company-logo">UniVerse</a>
        <nav class="company-nav">
            <a href="<?= BASE_URL ?>/company/landing">Dashboard</a>
            <a href="<?= BASE_URL ?>/company/managejobs">Manage Jobs</a>
            <a href="<?= BASE_URL ?>/company/postjobs">Post Jobs</a>
            <a href="<?= BASE_URL ?>/company/applications" class="active">View Applications</a>
        </nav>
        
        <div class="user-profile-dropdown">
            <div class="profile-trigger">
                <div class="profile-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="profile-info">
                    <span class="profile-name"><?= $user->firstname ?? 'User' ?></span>
                    <span class="profile-role">Company</span>
                </div>
                <div class="dropdown-arrow">▼</div>
            </div>
            
            <div class="dropdown-menu">
                <a href="<?= BASE_URL ?>/company/profile" class="dropdown-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                    Update Profile
                </a>
                <a href="<?= BASE_URL ?>/login/logout" class="dropdown-item logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
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
                    <option value="Software Engineer">Software Engineer</option>
                    <option value="UX Designer">UX Designer</option>
                    <option value="Product Manager">Product Manager</option>
                    <option value="Frontend Developer">Frontend Developer</option>
                    <option value="Data Analyst">Data Analyst</option>
                </select>
                
                <select id="applicationStatusFilter" class="filter-select form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="New">New</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Interview">Interview</option>
                    <option value="Hired">Hired</option>
                    <option value="Rejected">Rejected</option>
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
                    <tr data-applicant="John Smith" data-email="john.smith@email.com" data-position="Software Engineer" data-date="2025-08-29" data-status="New">
                        <td>
                            <div class="applicant-info">
                                <strong>John Smith</strong>
                                <div class="applicant-email">john.smith@email.com</div>
                            </div>
                        </td>
                        <td>Software Engineer</td>
                        <td>Aug 29, 2025</td>
                        <td><span class="badge badge-success">New</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-secondary">Interview</button>
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr data-applicant="Sarah Johnson" data-email="sarah.j@email.com" data-position="UX Designer" data-date="2025-08-28" data-status="Reviewed">
                        <td>
                            <div class="applicant-info">
                                <strong>Sarah Johnson</strong>
                                <div class="applicant-email">sarah.j@email.com</div>
                            </div>
                        </td>
                        <td>UX Designer</td>
                        <td>Aug 28, 2025</td>
                        <td><span class="badge badge-warning">Reviewed</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-secondary">Interview</button>
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr data-applicant="Mike Chen" data-email="mike.chen@email.com" data-position="Product Manager" data-date="2025-08-27" data-status="Interview">
                        <td>
                            <div class="applicant-info">
                                <strong>Mike Chen</strong>
                                <div class="applicant-email">mike.chen@email.com</div>
                            </div>
                        </td>
                        <td>Product Manager</td>
                        <td>Aug 27, 2025</td>
                        <td><span class="badge badge-info">Interview</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-success">Hire</button>
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr data-applicant="Emily Davis" data-email="emily.davis@email.com" data-position="Frontend Developer" data-date="2025-08-30" data-status="New">
                        <td>
                            <div class="applicant-info">
                                <strong>Emily Davis</strong>
                                <div class="applicant-email">emily.davis@email.com</div>
                            </div>
                        </td>
                        <td>Frontend Developer</td>
                        <td>Aug 30, 2025</td>
                        <td><span class="badge badge-success">New</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-secondary">Interview</button>
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr data-applicant="Robert Wilson" data-email="r.wilson@email.com" data-position="Data Analyst" data-date="2025-08-26" data-status="Reviewed">
                        <td>
                            <div class="applicant-info">
                                <strong>Robert Wilson</strong>
                                <div class="applicant-email">r.wilson@email.com</div>
                            </div>
                        </td>
                        <td>Data Analyst</td>
                        <td>Aug 26, 2025</td>
                        <td><span class="badge badge-warning">Reviewed</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                                <button class="btn btn-sm btn-secondary">Interview</button>
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </div>
                        </td>
                    </tr>
                    <tr data-applicant="Lisa Anderson" data-email="lisa.a@email.com" data-position="Product Manager" data-date="2025-08-25" data-status="Hired">
                        <td>
                            <div class="applicant-info">
                                <strong>Lisa Anderson</strong>
                                <div class="applicant-email">lisa.a@email.com</div>
                            </div>
                        </td>
                        <td>Product Manager</td>
                        <td>Aug 25, 2025</td>
                        <td><span class="badge badge-success">Hired</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">View</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

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
    </script>
</body>
</html>
