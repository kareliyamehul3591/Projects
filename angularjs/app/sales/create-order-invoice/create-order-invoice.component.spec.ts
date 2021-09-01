import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateOrderInvoiceComponent } from './create-order-invoice.component';

describe('CreateOrderInvoiceComponent', () => {
  let component: CreateOrderInvoiceComponent;
  let fixture: ComponentFixture<CreateOrderInvoiceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreateOrderInvoiceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateOrderInvoiceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
