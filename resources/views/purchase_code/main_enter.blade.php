@extends('purchase_code.enter')

@section('heading', 'License Verification')

@section('form_action', route('purchase.code.store'))

@section('error_session_key', 'purchase_code_error')
@section('error_type_session_key', 'error_type')
