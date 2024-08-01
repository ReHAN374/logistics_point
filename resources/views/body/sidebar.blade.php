<nav class="sidebar">
    <?php $page_name = basename($_SERVER['PHP_SELF']); ?>
    <ul class="list-unstyled">
        <li><a href="{{ route('users') }}" class="{{ Request::is('users') ? 'active' : '' }}">All Users</a></li>
        <li><a href="{{ route('product') }}" class="{{ Request::is('product') ? 'active' : '' }}">Product</a></li>
        <li><a href="{{ route('invoice') }}" class="{{ Request::is('invoice') ? 'active' : '' }}">Invoice</a></li>
        <li><a href="{{ route('issue_notes') }}" class="{{ Request::is('issue_notes') ? 'active' : '' }}">Issue Notes</a>
        <li><a href="{{ route('balance_notes') }}" class="{{ Request::is('balance_notes') ? 'active' : '' }}">Balance
                Notes</a>
        </li>
        <li><a href="{{ route('delivery_notes') }}"
                class="{{ Request::is('delivery_notes') ? 'active' : '' }}">Delivery
                Notes</a>
        </li>
        <li><a href="{{ route('purchase_order') }}"
                class="{{ Request::is('purchase_order') ? 'active' : '' }}">Purchase Order</a>
        </li>
        @if (Auth::user()->user_type == 1)
            <li><a href="{{ route('warehouse') }}"
                    class="{{ Request::is('warehouse') ? 'active' : '' }}">Warehouse</a>
            </li>
            <li>
                <a href="#reportsSubmenu" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle">Reports</a>
                <ul class="collapse list-unstyled {{ Request::is('invoice_report') ? 'show' : (Request::is('invoice_sale_report') ? 'show' : (Request::is('invoice_stats') ? 'show' : '')) }}"
                    id="reportsSubmenu">
                    <li><a href="{{ route('invoice_report') }}"
                            class="{{ Request::is('invoice_report') ? 'active' : '' }}">Invoice Report</a></li>
                    <li><a href="{{ route('invoice_sale_report') }}"
                            class="{{ Request::is('invoice_sale_report') ? 'active' : '' }}">Daily Sales Summary</a>
                    </li>
                    <li><a href="{{ route('invoice_stats') }}"
                            class="{{ Request::is('invoice_stats') ? 'active' : '' }}">Invoice Stats</a></li>
                    <li><a href="{{ route('invoice_outstanding_stats') }}"
                            class="{{ Request::is('invoice_outstanding_stats') ? 'active' : '' }}">Invoice Outstanding
                            Report</a></li>
                </ul>
            </li>
        @endif
    </ul>
</nav>
