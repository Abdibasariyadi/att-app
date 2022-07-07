<!-- Custom Tabs -->
<ul class="nav nav-pills">
    <li class="nav-item"><a href="{{ url('') }}/employee/{{ Request::segment(2) }}/edit" class="nav-link {{ (request()->segment(3) == 'edit') ? 'active' : '' }}"><i class="fa fa-user"></i> Employee Data</a>
    </li>
    <li class="nav-item"><a href="{{ url('') }}/employee/{{ Request::segment(2) }}/shiftwork" class="nav-link {{ (request()->segment(3) == 'shiftwork') ? 'active' : '' }}" aria-controls="profile"><i class="fa fa-calendar"></i> Shift Work</a>
    </li>
    <li class="nav-item"><a href="{{ url('') }}/employee/{{ Request::segment(2) }}/attendance" class="nav-link {{ (request()->segment(3) == 'attendance') ? 'active' : '' }}" aria-controls="profile"><i class="fa fa-calendar-check-o"></i> Attendance History</a>
    </li>
    <li class="nav-item"><a href="{{ url('') }}/employee/{{ Request::segment(2) }}/overtime" class="nav-link {{ (request()->segment(3) == 'overtime') ? 'active' : '' }}" aria-controls="contact"><i class="fa fa-calendar-plus-o"></i> Overtime History</a>
    </li>
</ul>
