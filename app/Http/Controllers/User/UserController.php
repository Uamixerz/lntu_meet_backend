<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\user\UserResource;
use App\Models\Images;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Отримуємо параметри запиту
        $facultyId = $request->faculty_id;
        $interests = $request->interests;
        $shownUserIds = $request->shown_user_ids ?? [];

        // Створюємо базовий запит
        $query = User::query();

        // Якщо факультет вказано, додаємо фільтр по факультету
        if ($facultyId != 0) {
            $query->where('faculty_id', $facultyId);
        }

        // Якщо інтереси вказано, додаємо фільтр по інтересам
        if (!empty($interests)) {
            $query->whereHas('interests', function($q) use ($interests) {
                $q->whereIn('interests.id', $interests);
            });
        }
        // Виключаємо вже показаних користувачів
        if (!empty($shownUserIds)) {
            $query->whereNotIn('telegramID', $shownUserIds);
        }

        $users = $query->paginate(10);

        // Повертаємо результат
        return UserResource::collection($users);

    }
    public function store(StoreRequest $request)
    {
        $data = $request->validated(); // Отримуємо валідовані дані
        // Отримуємо перший ключ з даних
        $key = array_key_first($data);
        // Створення нового користувача
        $user = User::create([
            'name' => $data[$key]['name'],
            'age' => $data[$key]['age'],
            'course' => $data[$key]['course'],
            'faculty_id' => $data[$key]['faculty'],
            'about' => $data[$key]['about'],
            'phone' => $data[$key]['phone'],
            'telegramID' => $key,
        ]);

        // Прив'язка інтересів до користувача через зв'язок багато-до-багатьох
        $user->interests()->sync($data[$key]['interest']??[]); // Використовуємо sync() для заміни поточних зв'язків

        // Відповідь з успішним результатом
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ], 200);
    }

    public function storeImage(Request $request)
    {
        // Валідація даних
        $request->validate([
            'telegramID' => 'required|integer',
            'paths' => 'required|array', // Має бути масив
            'paths.*' => 'required|string', // Кожен елемент масиву - рядок (шлях)
        ]);

        // Отримуємо telegramID з запиту
        $telegramID = $request->input('telegramID');
        // Видалення попередніх зображень
        Images::where('user_id', $telegramID)->delete();

        // Отримуємо масив шляхів до зображень
        $paths = $request->input('paths');

        // Зберігаємо кожен шлях у таблиці images з прив'язкою до telegramID
        foreach ($paths as $path) {
            Images::create([
                'user_id' => $telegramID, // Telegram ID користувача
                'image_path' => $path, // Шлях до зображення
            ]);
        }

        return response()->json(['message' => 'Images saved successfully'], 200);
    }


    public function update(StoreRequest $request)
    {
        $data = $request->validated(); // Отримуємо валідовані дані

        // Отримуємо перший ключ з даних
        $key = array_key_first($data);

        $user = User::where('telegramID', $key)->firstOrFail();

        // Оновлюємо інформацію користувача
        $user->update([
            'name' => $data[$key]['name'],
            'age' => $data[$key]['age'],
            'course' => $data[$key]['course'],
            'faculty_id' => $data[$key]['faculty'],
            'phone' => $data[$key]['phone'],
            'about' => $data[$key]['about'],
        ]);

        // Оновлюємо інтереси користувача (через зв'язок багато-до-багатьох)
        if (isset($data[$key]['interest'])) {
            $user->interests()->sync($data[$key]['interest']); // Оновлюємо інтереси користувача
        }

        // Відповідь з успішним результатом
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }
}
