<div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
        <li>
            <a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-file-text fa-fw"></i> Template Store<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('user.template.create') }}">Create Template</a>
                </li>
                <li>
                    <a href="{{ route('user.template.get.index') }}">Template List</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>
    </ul>
</div>
