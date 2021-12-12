<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">

        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget='treeview' role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @admin()
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-pdf nav-icon"></i>
                        <p>
                            Report
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('vendorQuotes.report.' . auth()->user()->user_role) }}" class="nav-link">
                                <i class="fa fa-minus"></i>
                                <p>VendorWise</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inquiry.salewise.' . auth()->user()->user_role) }}" class="nav-link">
                                <i class="fa fa-minus"></i>
                                <p>Inquiry SalePerson Wise </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('inquiry.datewise.' . auth()->user()->user_role) }}" class="nav-link">
                                <i class="fa fa-minus"></i>
                                <p>Inquiry DateWise </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('report.itemwise.' . auth()->user()->user_role) }}" class="nav-link">
                                <i class="fa fa-minus"></i>
                                <p>ItemWise </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('report.quotationwise.' . auth()->user()->user_role) }}" class="nav-link">
                                <i class="fa fa-minus"></i>
                                <p>QuotationWise </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('user.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-file nav-icon"></i>
                        <p>
                            Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.open.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-pen-square nav-icon"></i>
                        <p>
                            Open Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customer.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>
                            Customer
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('item.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-boxes nav-icon"></i>
                        <p>
                            Items
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('brand.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-certificate nav-icon"></i>
                        <p>
                            Brand
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('category.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-bezier-curve nav-icon"></i>
                        <p>
                            Category
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customerquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-receipt nav-icon"></i>
                        <p>
                            Customer Quotation
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('vendorquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-paste nav-icon"></i>
                        <p>
                            Vendor Quotation
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('vendor.add.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-paste nav-icon"></i>
                        <p>
                            Vendor
                        </p>
                    </a>
                </li>
                @endadmin

                @manager()
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-file nav-icon"></i>
                        <p>
                            Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.open.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-pen-square nav-icon"></i>
                        <p>
                            Open Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customer.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>
                            Customer
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('item.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-boxes nav-icon"></i>
                        <p>
                            Items
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customerquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-receipt nav-icon"></i>
                        <p>
                            Customer Quotation
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('vendorquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-paste nav-icon"></i>
                        <p>
                            Vendor Quotation
                        </p>
                    </a>
                </li>
                @endmanager

                @sale()
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.open.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-pen-square nav-icon"></i>
                        <p>
                            Open Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>
                            My Submitted Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('quotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>
                            Quotation
                        </p>
                    </a>
                </li>
                @endsale

                @team()
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-file nav-icon"></i>
                        <p>
                            Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('inquiry.open.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-pen-square nav-icon"></i>
                        <p>
                            Open Inquiry
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customer.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>
                            Customer
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('item.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-boxes nav-icon"></i>
                        <p>
                            Items
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('customerquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-receipt nav-icon"></i>
                        <p>
                            Customer Quotation
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="{{ route('vendorquotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-paste nav-icon"></i>
                        <p>
                            Vendor Quotation
                        </p>
                    </a>
                </li>
                @endteam

                <li class="nav-item mb-5">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
