# This is just example, you can delete this migration
create table test
(
    id         varchar(255)                        not null,
    value      varchar(255)                        null,
    updated_at DATETIME  default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    created_at timestamp default CURRENT_TIMESTAMP not null,
    constraint test primary key (id)
);