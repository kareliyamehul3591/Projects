import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";
import { Routes, RouterModule } from "@angular/router";
import { AddEmployeeComponent } from "./employee/add-employee/add-employee.component";
import { DashboardComponent } from "./dashboard/dashboard.component";
import { HeaderComponent } from "./slizzing/header/header.component";
import { ShowEmployeeComponent } from "./employee/show-employee/show-employee.component";
import { SidebarComponent } from "./slizzing/sidebar/sidebar.component";
import { LoginComponent } from "./login/login/login.component";
import { AddAttendanceComponent } from "./attendance/add-attendance/add-attendance.component";
import { ApplyLeaveComponent } from "./attendance/apply-leave/apply-leave.component";
import { ShowEmpLeaveComponent } from "./employee/show-emp-leave/show-emp-leave.component";
import { EmpLeaveDetailsComponent } from "./employee/emp-leave-details/emp-leave-details.component";
import { FormSixteenComponent } from "./my-offical-documents/form-sixteen/form-sixteen.component";
import { SalarySlipComponent } from "./my-offical-documents/salary-slip/salary-slip.component";
import { CompanyPolicyComponent } from "./my-offical-documents/company-policy/company-policy.component";
import { ShowEmpAttendComponent } from "./employee/show-emp-attend/show-emp-attend.component";
import { ViewEmpComponent } from "./employee/view-emp/view-emp.component";
import { EditEmployeeComponent } from "./employee/edit-employee/edit-employee.component";
import { CreatePolicyComponent } from "./my-offical-documents/create-policy/create-policy.component";
import { CreateForm16Component } from "./my-offical-documents/create-form16/create-form16.component";
import { CreateSalarySlipComponent } from "./my-offical-documents/create-salary-slip/create-salary-slip.component";
import { AddEnquiryComponent } from "./enquiry/add-enquiry/add-enquiry.component";
import { AddExpenseComponent } from "./expense/add-expense/add-expense.component";
import { AuthGuard } from "./auth.guard";
import { EditattendComponent } from "./employee/editattend/editattend.component";
import { ForgetPassComponent } from "./login/forget-pass/forget-pass.component";
import { ShowSalaryComponent } from "./my-offical-documents/show-salary/show-salary.component";
import { ViewSalaryComponent } from "./my-offical-documents/view-salary/view-salary.component";
import { EditSalaryComponent } from "./my-offical-documents/edit-salary/edit-salary.component";
import { GenerateSlipComponent } from "./my-offical-documents/generate-slip/generate-slip.component";
import { SalaryHistoryComponent } from "./my-offical-documents/salary-history/salary-history.component";
import { HolidayCalenderComponent } from "./attendance/holiday-calender/holiday-calender.component";
import { ViewGenSalaryComponent } from "./my-offical-documents/view-gen-salary/view-gen-salary.component";
import { UpdategensalaryComponent } from "./my-offical-documents/updategensalary/updategensalary.component";
import { ViewEnquiryComponent } from "./enquiry/view-enquiry/view-enquiry.component";
import { UpdateEnquiryComponent } from "./enquiry/update-enquiry/update-enquiry.component";
import { SalarySheetComponent } from "./my-offical-documents/salary-sheet/salary-sheet.component";
import { PdfSalarySlipComponent } from "./my-offical-documents/pdf-salary-slip/pdf-salary-slip.component";
import { ChangePassComponent } from "./login/change-pass/change-pass.component";
import { ChangepasswordComponent } from "./login/changepassword/changepassword.component";
import { ProfileComponent } from "./login/profile/profile.component";
import { AddLocationComponent } from "./employee/add-location/add-location.component";
import { AddleadComponent } from "./sales/addlead/addlead.component";
import { EditleadComponent } from "./sales/editlead/editlead.component";
import { LeadlistComponent } from "./sales/leadlist/leadlist.component";
import { ViewleadComponent } from "./sales/viewlead/viewlead.component";
import { CreateOrderComponent } from "./sales/create-order/create-order.component";
import { ListOrderComponent } from "./sales/list-order/list-order.component";
import { EditOrderComponent } from "./sales/edit-order/edit-order.component";
import { CreateVendorComponent } from "./sales/create-vendor/create-vendor.component";
import { ListVendorComponent } from "./sales/list-vendor/list-vendor.component";
import { CreateMilestonesComponent } from "./sales/create-milestones/create-milestones.component";
import { ListMilestonesComponent } from "./sales/list-milestones/list-milestones.component";
import { ListOrderInvoiceComponent } from "./sales/list-order-invoice/list-order-invoice.component";
import { CreateOrderInvoiceComponent } from "./sales/create-order-invoice/create-order-invoice.component";
import { ListCustomerComponent } from "./sales/list-customer/list-customer.component";
import { CreateCustomerComponent } from "./sales/create-customer/create-customer.component";
import { ListPurchaseComponent } from "./purchase/list-purchase/list-purchase.component";
import { CreatePurchaseComponent } from "./purchase/create-purchase/create-purchase.component";
import { ListPurchaseRequestComponent } from "./purchase/list-purchase-request/list-purchase-request.component";
import { CreatePurchaseRequestComponent } from "./purchase/create-purchase-request/create-purchase-request.component";
import { ViewVendorComponent } from "./sales/view-vendor/view-vendor.component";
import { EditVendorComponent } from "./sales/edit-vendor/edit-vendor.component";
import { EditInvoiceComponent } from "./sales/edit-invoice/edit-invoice.component";
import { ViewInvoiceComponent } from "./sales/view-invoice/view-invoice.component";
import { ViewCustomerComponent } from "./sales/view-customer/view-customer.component";
import { EditCustomerComponent } from "./sales/edit-customer/edit-customer.component";
import { QuotationComponent } from "./purchase/quotation/quotation.component";
import { MaterialReceiptComponent } from "./purchase/material-receipt/material-receipt.component";
import { PurchaseInvoiceComponent } from "./purchase/purchase-invoice/purchase-invoice.component";
import { ImportantNoteComponent } from "./invoice-setting/important-note/important-note.component";
import { OfficeAddressComponent } from "./invoice-setting/office-address/office-address.component";
import { RegisterAddressComponent } from "./invoice-setting/register-address/register-address.component";
import { UploadLogoComponent } from "./invoice-setting/upload-logo/upload-logo.component";
import { TermCondComponent } from "./invoice-setting/term-cond/term-cond.component";
import { AddTermCondComponent } from "./invoice-setting/add-term-cond/add-term-cond.component";
import { AddRegisterationComponent } from "./invoice-setting/add-registeration/add-registeration.component";
import { ViewPurchaseReqComponent } from "./purchase/view-purchase-req/view-purchase-req.component";
import { EditPurchaseReqComponent } from "./purchase/edit-purchase-req/edit-purchase-req.component";
import { EditMilestoneComponent } from "./sales/edit-milestone/edit-milestone.component";
import { EditPurchaseOrderComponent } from "./purchase/edit-purchase-order/edit-purchase-order.component";
import { ViewPurchaseOrderComponent } from "./purchase/view-purchase-order/view-purchase-order.component";

const routes: Routes = [
  //login
  { path: "", component: LoginComponent, pathMatch: "full" },
  { path: "login", component: LoginComponent, pathMatch: "full" },
  { path: "forget-pass", component: ForgetPassComponent, pathMatch: "full" },
  { path: "change-pass/:id", component: ChangePassComponent },
  {
    path: "changepassword",
    component: ChangepasswordComponent,
    canActivate: [AuthGuard],
  },
  { path: "profile", component: ProfileComponent, canActivate: [AuthGuard] },
  //employee
  {
    path: "addemp",
    component: AddEmployeeComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "showemp",
    component: ShowEmployeeComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "view-emp",
    component: ViewEmpComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "edit-emp/:id",
    component: EditEmployeeComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  //dashboard
  {
    path: "dashboard1",
    component: DashboardComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //header
  {
    path: "header",
    component: HeaderComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //sidebar
  {
    path: "sidebar",
    component: SidebarComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //attendance
  {
    path: "attendance",
    component: AddAttendanceComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "emp-attend",
    component: ShowEmpAttendComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "edit-attend",
    component: EditattendComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //leave
  {
    path: "applyleave",
    component: ApplyLeaveComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "showemp-leave",
    component: ShowEmpLeaveComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "empleave-details",
    component: EmpLeaveDetailsComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //form 16
  {
    path: "create-form16",
    component: CreateForm16Component,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "form-sixteen",
    component: FormSixteenComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //salary
  {
    path: "salary-slip",
    component: SalarySlipComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "create-salaryslip",
    component: CreateSalarySlipComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "show-salary",
    component: ShowSalaryComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "view-salary",
    component: ViewSalaryComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "edit-salary/:id",
    component: EditSalaryComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "generate-salary",
    component: GenerateSlipComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "history-salary",
    component: SalaryHistoryComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "view-gen-salary",
    component: ViewGenSalaryComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "update-gen-salary",
    component: UpdategensalaryComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-salary-sheet",
    component: SalarySheetComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "pdf-salary-slip",
    component: PdfSalarySlipComponent,
    canActivate: [AuthGuard],
  },

  //policy
  {
    path: "policy",
    component: CompanyPolicyComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "create-policy",
    component: CreatePolicyComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //enquiry
  {
    path: "view_invoice/:id",
    component: AddEnquiryComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },
  {
    path: "view-enquiry",
    component: ViewEnquiryComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "update-enquiry",
    component: UpdateEnquiryComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-lead-info",
    component: AddleadComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-lead-info/:id",
    component: EditleadComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "list-lead-info",
    component: LeadlistComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "view-lead-info",
    component: ViewleadComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-order",
    component: CreateOrderComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "list-order",
    component: ListOrderComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-order/:id",
    component: EditOrderComponent,
    canActivate: [AuthGuard],
  },

  //expense
  {
    path: "add-expense",
    component: AddExpenseComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //calender
  {
    path: "holiday-calender",
    component: HolidayCalenderComponent,
    pathMatch: "full",
    canActivate: [AuthGuard],
  },

  //location
  {
    path: "add-location",
    component: AddLocationComponent,
    canActivate: [AuthGuard],
  },

  //vendor
  {
    path: "add-vendor",
    component: CreateVendorComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "list-vendor",
    component: ListVendorComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "view-vendor",
    component: ViewVendorComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-vendor/:id",
    component: EditVendorComponent,
    canActivate: [AuthGuard],
  },

  // milestone list,add routing
  {
    path: "milestone-list",
    component: ListMilestonesComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-milestone-info",
    component: CreateMilestonesComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-milestone-info/:order_type",
    component: EditMilestoneComponent,
    canActivate: [AuthGuard],
  },

  //order-invoice
  {
    path: "order-invoice-list",
    component: ListOrderInvoiceComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-order-invoice/:id",
    component: CreateOrderInvoiceComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-order-invoice/:id",
    component: EditInvoiceComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "view-order-invoice",
    component: ViewInvoiceComponent,
    canActivate: [AuthGuard],
  },

  // customer
  {
    path: "customer-list",
    component: ListCustomerComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-customer/:id",
    component: CreateCustomerComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "customer-list",
    component: ViewCustomerComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-customer/:id",
    component: EditCustomerComponent,
    canActivate: [AuthGuard],
  },

  //purchase order
  {
    path: "purchase-order-list",
    component: ListPurchaseComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-purchase-order",
    component: CreatePurchaseComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-purchase-order/:id",
    component: EditPurchaseOrderComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "view-purchase-order/:id",
    component: ViewPurchaseOrderComponent,
    canActivate: [AuthGuard],
  },
  //purchase request
  {
    path: "purchase-request-list",
    component: ListPurchaseRequestComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-purchase-request",
    component: CreatePurchaseRequestComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "view-purchase-request/:id",
    component: ViewPurchaseReqComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "edit-purchase-request/:id",
    component: EditPurchaseReqComponent,
    canActivate: [AuthGuard],
  },

  //quotation
  {
    path: "quotation-list",
    component: QuotationComponent,
    canActivate: [AuthGuard],
  },

  //material-receipt
  {
    path: "material-receipt-list",
    component: MaterialReceiptComponent,
    canActivate: [AuthGuard],
  },

  //purchase invoice
  {
    path: "purchase invoice-list",
    component: PurchaseInvoiceComponent,
    canActivate: [AuthGuard],
  },

  //invoice setting
  {
    path: "important-note",
    component: ImportantNoteComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "office-address",
    component: OfficeAddressComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "register-address",
    component: RegisterAddressComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "upload-logo",
    component: UploadLogoComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "term-condition",
    component: TermCondComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-term-condition",
    component: AddTermCondComponent,
    canActivate: [AuthGuard],
  },
  {
    path: "add-registeration",
    component: AddRegisterationComponent,
    canActivate: [AuthGuard],
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { useHash: true }),
    /// RouterModule.forRoot(routes, { onSameUrlNavigation: 'reload' })
  ],
  exports: [RouterModule],
})
export class AppRoutingModule {}
