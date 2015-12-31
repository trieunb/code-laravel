<div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
        <li>
            <a href="{{URL::to('/admin')}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-file-text fa-fw"></i> User management<span class="fa arrow"></span></a>
             <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('admin.user.get.index') }}">User List</a>
                </li>
                <li>
                    <a href="{{ route('admin.user.get.send-notification') }}">Send Notification</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="fa fa-file-text fa-fw"></i> Market Place<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('admin.template.get.create') }}">Create Template</a>
                </li>
                <li>
                    <a href="{{ route('admin.template.get.index') }}">Template List</a>
                </li>
                <li>
                    <a href="#"><i class="glyphicon glyphicon-th-list"></i> Category<span class="fa arrow"></span></a>
                    <ul class="nav nav-third-level">
                        <li>
                            <a href="{{ route('admin.category.get.create') }}">Create Category</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.category.get.index') }}">Category List</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#"><i class="fa fa-question fa-fw"></i> Question<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('admin.question.get.create') }}">Create Question</a>
                </li>
                <li>
                    <a href="{{ route('admin.question.get.index') }}">Question List</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>
        <li>
            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Report<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse" aria-expanded="true">
                <li>
                    <a href="{{ route('admin.report.user.month') }}">Users</a>
                </li>
                <li>
                    <a href="{{ route('admin.report.template') }}">Templates</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>
    </ul>
</div>
