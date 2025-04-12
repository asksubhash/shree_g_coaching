@extends('errors.custom-illustrated-layout')

@section('title', __('Error: '.$title))
@section('code', __('Error: '.$title))
@section('message', __('Message: '.$message))
