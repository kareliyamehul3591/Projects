import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListOrderInvoiceComponent } from './list-order-invoice.component';

describe('ListOrderInvoiceComponent', () => {
  let component: ListOrderInvoiceComponent;
  let fixture: ComponentFixture<ListOrderInvoiceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListOrderInvoiceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListOrderInvoiceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
