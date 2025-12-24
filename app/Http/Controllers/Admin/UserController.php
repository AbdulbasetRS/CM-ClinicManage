<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BloodType;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Exceptions\UserNotFoundException;
use App\Helpers\PathHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::select([
                'id',
                'username',
                'slug',
                'email',
                'mobile_number',
                'national_id',
                'status',
                'type',
                'can_login',
                'status_details',
                'created_at',
            ]);

            // Filter by status if selected
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by type if selected
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            $users = $query->get();

            $userResource = UserResource::collection($users)->response()->getData(true);

            return DataTables::of($userResource['data'])
                ->addColumn('action', function ($user) {
                    return '
                    <a href="'.route('admin.users.show', $user['slug']).'" class="btn btn-sm btn-info">'.__('admin.show').'</a>
                    <a href="'.route('admin.users.edit', $user['slug']).'" class="btn btn-sm btn-warning">'.__('admin.edit').'</a>
                    <a href="'.route('admin.users.destroy', $user['slug']).'" class="btn btn-sm btn-danger">'.__('admin.delete').'</a>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $statuses = UserStatus::cases();
        $types = UserType::cases();

        return view('admin.users.index', compact('statuses', 'types'));
    }

    public function create()
    {
        $statuses = UserStatus::cases();
        $types = UserType::cases();

        return view('admin.users.create', compact('statuses', 'types'));
    }

    public function create_doctor()
    {
        $statuses = UserStatus::cases();
        $types = UserType::cases();

        return view('admin.users.create-doctor', compact('statuses', 'types'));
    }

    public function create_patient()
    {
        $statuses = UserStatus::cases();
        $types = UserType::cases();
        $bloodTypes = BloodType::cases();

        return view('admin.users.create-patient', compact('statuses', 'types', 'bloodTypes'));
    }

    public function store(UserStoreRequest $request)
    {
        $user = new User;

        $data = $request->userData();

        $typeValue = $data['type'] instanceof \UnitEnum ? $data['type']->value : $data['type'];

        // If patient, set can_login to false and generate random password if empty
        if ($typeValue === 'patient') {
            $data['can_login'] = false;
            if (empty($request->password)) {
                $request->merge(['password' => \Illuminate\Support\Str::random(12)]);
            }
        }

        $user->fill($data);
        $user->password = $request->password;
        $user->save();

        // Create and fill profile
        $profile = $user->profile()->create($request->profileData());

        // Handle avatar if uploaded
        if ($request->hasFile('avatar')) {
            $filename = PathHelper::storeUserAvatar($user->id, $request->file('avatar'));
            $profile->avatar = $filename;
            $profile->save();
        }

        return redirect()
            ->route('admin.users.edit', $user->slug)
            ->with('success', __('admin.user_created_successfully'));
    }

    public function show($slug)
    {
        $user = User::where('slug', $slug)->first();

        if (! $user) {
            throw new UserNotFoundException;
            // return redirect()->route('admin.users.index')->with('error', __('admin.user_not_found'));
        }
        $user = new UserResource($user);

        // return $user;
        return view('admin.users.show', compact('user'));
    }

    public function edit($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $user = new UserResource($user);

        // return $user;
        // $user = json_decode(json_encode($user)) ;
        $statuses = UserStatus::cases();
        $types = UserType::cases();

        return view('admin.users.edit', compact('user', 'statuses', 'types'));
    }

    public function update(UserUpdateRequest $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $user->fill($request->userData());

        if ($user->profile) {
            $user->profile->fill($request->profileData());
        }

        if ($request->hasFile('avatar')) {
            if ($user->profile && $user->profile->avatar) {
                PathHelper::deleteUserAvatar($user->id, $user->profile->avatar);
            }

            $filename = PathHelper::storeUserAvatar($user->id, $request->file('avatar'));

            if ($user->profile) {
                $user->profile->avatar = $filename;
            }
        }

        if ($request->filled('password')) {
            $user->password = $request->password; // لا حاجة لـ Hash::make
        }

        $userChanged = $user->isDirty();
        $profileChanged = $user->profile?->isDirty();

        if (! $userChanged && ! $profileChanged) {
            return redirect()
                ->route('admin.users.edit', $user->slug)
                ->with('info', __('admin.no_changes_made'));
        }

        // 💾 Save user and profile together
        $user->push();

        // ✅ Done
        return redirect()
            ->route('admin.users.edit', $user->slug)
            ->with('success', __('admin.user_updated_successfully'));
    }

    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', __('admin.user_deleted_successfully'));
    }

    public function search_patient(Request $request)
    {
        $query = $request->get('q');

        return User::where('type', 'patient')
            ->where(function ($q2) use ($query) {
                $q2->where('username', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->orWhere('mobile_number', 'like', "%$query%")
                    ->orWhere('national_id', 'like', "%$query%");
            })

            ->limit(20)
            ->get(['id', 'username', 'email', 'mobile_number', 'national_id']);
    }
}
