use commission;
delete from commission_area_price_float_ratio;
delete from commission_contact_detail;
delete from commission_contact_main;
delete from commission_customer;
delete from commission_customer_funds;

--fee_ratio表不建议删除，里面所有业务员的扣率
--delete from commission_fee_ratio;

delete from commission_funds_back;
delete from commission_insurance_fund;
delete from commission_length_limit;
delete from commission_load_history;

--login表不建议删除，里面存储登陆人员信息
--delete from commission_login; 

delete from commission_normal_business_ratio;
delete from commission_normal_profit_ratio;
delete from commission_price_float_ratio;
delete from commission_salary;
delete from commission_sale_expense;
delete from commission_salesman;
delete from commission_special_business_ratio;
delete from commission_tax_ratio;
delete from commission_wage_deduction;