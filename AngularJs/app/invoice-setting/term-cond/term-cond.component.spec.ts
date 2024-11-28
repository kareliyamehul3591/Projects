import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TermCondComponent } from './term-cond.component';

describe('TermCondComponent', () => {
  let component: TermCondComponent;
  let fixture: ComponentFixture<TermCondComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TermCondComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TermCondComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
