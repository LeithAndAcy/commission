use commission;
truncate table commission_area_price_float_ratio;
truncate table commission_contact_detail;
truncate table commission_contact_main;
truncate table commission_customer;
truncate table commission_customer_funds;

--fee_ratio表不建议删除，里面所有业务员的扣率
--delete from commission_fee_ratio;

truncate table commission_funds_back;
truncate table commission_insurance_fund;
truncate table commission_length_limit;
truncate table commission_load_history;

--login表不建议删除，里面存储登陆人员信息
--delete from commission_login; 

truncate table commission_normal_business_ratio;
truncate table commission_normal_profit_ratio;
truncate table commission_price_float_ratio;
truncate table commission_salary;
truncate table commission_sale_expense;
truncate table commission_salesman;
truncate table commission_special_business_ratio;
truncate table commission_tax_ratio;
truncate table commission_wage_deduction;