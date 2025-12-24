@extends('admin.structure')

@section('title', __('admin.create') . ' ' . __('admin.visit_information'))

@section('content')


    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.visits.store') }}">
            @csrf

            <div class="row">
                {{-- Patient --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.patient') }}</label>

                    {{--
                    ✅ الشرط الجديد: التحقق إذا كان كائن المريض مُمرَّر (سواءً من صفحة المستخدم أو من الحجز)
                    نستخدم متغير $patient هنا، ونفترض أن الكنترولر قام بتعيينه.
                    --}}
                    @php
                        $currentPatient = $patient ?? ($appointment->patient ?? null);
                    @endphp

                    @if (isset($currentPatient) && $currentPatient)
                        {{-- حقل إدخال مخفي لتمرير الـ ID --}}
                        <input type="hidden" name="patient_id" value="{{ $currentPatient->id }}">

                        {{-- عرض اسم المريض ورابط لصفحته (ثابت ومغلق) --}}
                        <a href="{{ route('admin.users.show', $currentPatient->slug) }}" class="form-control text-primary"
                            target="_blank" style="">
                            {{ $currentPatient->username }}
                        </a>

                        {{-- عرض رسالة الخطأ في حال وجودها (نستخدم d-block لضمان ظهورها أسفل الرابط) --}}
                        @error('patient_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    @else
                        {{-- حالة عدم وجود مريض مُحدد: يتم عرض Select2 للبحث --}}
                        <select id="patientSelect" name="patient_id"
                            class="form-select @error('patient_id') is-invalid @enderror"></select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                {{-- Doctor --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.doctor') }}</label>
                    <select name="doctor_id" class="form-select  @error('doctor_id') is-invalid @enderror">
                        <option value="">{{ __('admin.select') }} {{ __('admin.doctor') }}</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                {{ $doctor->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Appointment --}}
                @if (isset($appointment) && $appointment)
                    <div class="col-md-12 mb-3">
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                            class="form-control text-primary" target="_blank" style="">
                            {{ __('admin.view') }} {{ __('admin.appointment_information') }} #{{ $appointment->id }}
                        </a>
                    </div>
                @endif

                {{-- Visit Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.visit_date') }}</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date') }}"
                        class="form-control @error('visit_date') is-invalid @enderror">
                    @error('visit_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ __('admin.status') }}</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status') == $status)>{{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Symptoms --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.symptoms') }}</label>
                    <textarea name="symptoms" class="form-control @error('symptoms') is-invalid @enderror">{{ old('symptoms') }}</textarea>
                    @error('symptoms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Diagnosis --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.diagnosis') }}</label>
                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror">{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Treatment Plan --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.treatment_plan') }}</label>
                    <textarea name="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror">{{ old('treatment_plan') }}</textarea>
                    @error('treatment_plan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="col-12 mb-3">
                    <label class="form-label">{{ __('admin.notes') }}</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">{{ __('admin.create') }} </button>
                <a href="{{ route('admin.visits.index') }}" class="btn btn-secondary">{{ __('admin.back') }}</a>
            </div>

        </form>
    </div>

    <script>
        $(document).ready(function() {
            // تحديد الاتجاه
            let isRtl = $('body').attr('dir') === 'rtl';
            let currentDir = isRtl ? 'rtl' : 'ltr';

            $('#patientSelect').select2({
                placeholder: isRtl ? 'اختر المريض…' : 'Select patient…',
                dir: currentDir,
                allowClear: true,
                minimumInputLength: 2,
                width: '100%', // <--- (1) هذا السطر هو الحل السحري لمشكلة الـ Resize

                language: {
                    inputTooShort: function(args) {
                        let remainingChars = args.minimum - args.input.length;
                        return isRtl ? "الرجاء كتابة " + remainingChars + " حرف أو أكثر" :
                            "Please enter " + remainingChars + " or more characters";
                    },
                    noResults: function() {
                        return isRtl ? "لا توجد نتائج" : "No results found";
                    },
                    searching: function() {
                        return isRtl ? "جاري البحث..." : "Searching...";
                    }
                },
                ajax: {
                    url: '{{ route('admin.api.patients.search') }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                let mobile_number = item.mobile_number || '';
                                let national_id = item.national_id || '';
                                let displayText = item.username;
                                if (mobile_number) displayText += ' - ' + mobile_number;
                                if (national_id) displayText += ' - ' + national_id;
                                return {
                                    id: item.id,
                                    text: displayText
                                };
                            })
                        };
                    }
                }
            });

            // نقل كلاس الخطأ
            if ($('#patientSelect').hasClass('is-invalid')) {
                $('#patientSelect').next('.select2-container').addClass('is-invalid');
            }
        });
    </script>
    <style>
        /* ========================================= */
        /* 1. الحاوية الأساسية (Container)           */
        /* ========================================= */
        .select2-container .select2-selection--single {
            height: 38px !important;

            /* الوضع الفاتح: لون أبيض مثل باقي الحقول */
            background-color: #ffffff !important;
            color: #212529 !important;
            /* لون النص الافتراضي */

            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            position: relative;
            display: flex;
            align-items: center;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        /* ========================================= */
        /* 2. تنسيق النص الداخلي                     */
        /* ========================================= */
        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block !important;
            line-height: 36px !important;
            height: 36px !important;
            padding: 0 !important;
            color: inherit !important;
            /* يرث اللون من الأب */
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            width: 100%;
        }

        /* ========================================= */
        /* 3. السهم (Arrow)                          */
        /* ========================================= */
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            width: 30px !important;
            position: absolute;
            top: 1px;
            z-index: 10;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 16px 12px;
            transition: transform 0.2s ease-in-out;
        }

        .select2-container .select2-selection--single .select2-selection__arrow b {
            display: none !important;
        }

        .select2-container--open .select2-selection--single .select2-selection__arrow {
            transform: rotate(180deg);
        }

        /* زر الحذف (X) */
        .select2-container .select2-selection--single .select2-selection__clear {
            position: absolute;
            height: 36px;
            line-height: 36px;
            top: 0;
            z-index: 20;
            color: #999;
            font-weight: bold;
            font-size: 16px;
        }

        .select2-container .select2-selection--single .select2-selection__clear:hover {
            color: #dc3545;
        }

        /* ========================================= */
        /* 4. ضبط الاتجاهات (RTL vs LTR)             */
        /* ========================================= */
        [dir="rtl"] .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 12px !important;
            padding-left: 35px !important;
        }

        [dir="rtl"] .select2-container .select2-selection--single .select2-selection__arrow {
            left: 1px;
            right: auto;
        }

        [dir="rtl"] .select2-container .select2-selection--single .select2-selection__clear {
            left: 30px;
            right: auto;
        }

        [dir="ltr"] .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 12px !important;
            padding-right: 35px !important;
        }

        [dir="ltr"] .select2-container .select2-selection--single .select2-selection__arrow {
            right: 1px;
            left: auto;
        }

        [dir="ltr"] .select2-container .select2-selection--single .select2-selection__clear {
            right: 30px;
            left: auto;
        }


        /* ========================================= */
        /* 5. الثيم الليلي (Dark Mode) - تعديل الألوان */
        /* ========================================= */

        /* تعديل خلفية الحقل لتطابق الـ inputs في الوضع الليلي */
        body.theme-dark .select2-container .select2-selection--single {
            background-color: #212529 !important;
            /* لون الحقول القياسي في بوتستراب دارك */
            border-color: rgba(255, 255, 255, 0.15) !important;
            /* لون الحدود في الدارك */
            color: #dfe6e9 !important;
            /* لون النص الفاتح */
        }

        /* عكس لون السهم للأبيض في الوضع الليلي */
        body.theme-dark .select2-container .select2-selection--single .select2-selection__arrow {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* القائمة المنسدلة في الوضع الليلي */
        body.theme-dark .select2-dropdown {
            background-color: #2d3436 !important;
            /* خلفية القائمة (ممكن نخليها زي البودي) */
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #dfe6e9 !important;
        }

        body.theme-dark .select2-results__option {
            color: #dfe6e9 !important;
        }

        body.theme-dark .select2-results__option--highlighted[aria-selected] {
            background-color: rgba(0, 123, 255, 0.3) !important;
            color: #fff !important;
        }

        body.theme-dark .select2-search__field {
            background-color: #1f2426 !important;
            color: #dfe6e9 !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* ========================================= */
        /* 6. حالات التركيز والخطأ                   */
        /* ========================================= */
        .select2-container--open .select2-selection--single {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .select2-container--default.is-invalid .select2-selection--single {
            border-color: #dc3545 !important;
        }
    </style>
@endsection
