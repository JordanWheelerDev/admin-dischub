<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="index">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                    aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Moderation
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="awaiting-approval">Awaiting Approval <span
                                class="badge text-bg-danger"
                                style="margin-left: 4px;"><?php echo getNumberOfAwaitingApprovalServers() ?></span></a>
                        <a class="nav-link" href="reports">Reports <span class="badge text-bg-danger"
                                style="margin-left: 4px;"><?php echo getNumberOfReports() ?></span></a>
                    </nav>
                </div>
                <?php if ($user_id == 1) { ?>
                    <a class="nav-link" href="add-user">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Add User
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo $user_name; ?>
        </div>
    </nav>
</div>