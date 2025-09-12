<?php

require_once __DIR__ . '/../model/UtilisateursRepository.php';

class UsersController
{
    private $usersRepo;

    public function __construct()
    {
        $this->usersRepo = new UsersRepository();
    }

    /**
     * Affiche la liste des utilisateurs
     */
    public function index(): array
    {
        $search = $_GET['search'] ?? '';
        $roleFilter = $_GET['role'] ?? '';
        $statutFilter = $_GET['statut'] ?? '';
        $alphaOrder = $_GET['alpha_order'] ?? '';
        $dateOrder = $_GET['date_order'] ?? 'desc';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;

        // Récupération des utilisateurs
        if ($search) {
            $users = $this->usersRepo->searchUsers($search);
        } else {
            $users = $this->usersRepo->getAllUsers();
        }

        // Filtre par rôle
        if ($roleFilter) {
            $users = array_filter($users, function($user) use ($roleFilter) {
                return $user['role'] === $roleFilter;
            });
        }

        // Filtre par statut
        if ($statutFilter) {
            $users = array_filter($users, function($user) use ($statutFilter) {
                return $user['statut'] === $statutFilter;
            });
        }

        // Tri alphabétique
        if ($alphaOrder) {
            usort($users, function($a, $b) use ($alphaOrder) {
                $nameA = $a['nom'] . ' ' . $a['prenom'];
                $nameB = $b['nom'] . ' ' . $b['prenom'];
                if ($alphaOrder === 'asc') {
                    return strcmp($nameA, $nameB);
                } else {
                    return strcmp($nameB, $nameA);
                }
            });
        }

        // Tri chronologique
        if ($dateOrder) {
            usort($users, function($a, $b) use ($dateOrder) {
                if ($dateOrder === 'asc') {
                    return strtotime($a['add_date']) - strtotime($b['add_date']);
                } else {
                    return strtotime($b['add_date']) - strtotime($a['add_date']);
                }
            });
        }

        // Pagination
        $total = count($users);
        $pages = ceil($total / $perPage);
        $users = array_slice($users, ($page - 1) * $perPage, $perPage);

        return [
            'users' => $users,
            'total' => $total,
            'pages' => $pages,
            'currentPage' => $page,
            'filters' => [
                'search' => $search,
                'role' => $roleFilter,
                'statut' => $statutFilter,
                'alpha_order' => $alphaOrder,
                'date_order' => $dateOrder
            ]
        ];
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function show($id)
    {
        return $this->usersRepo->getUserById($id);
    }

    /**
     * Met à jour un utilisateur
     */
    public function update($id, $data)
    {
        $nom = $data['nom'] ?? '';
        $prenom = $data['prenom'] ?? '';
        $email = $data['email'] ?? '';
        $role = $data['role'] ?? 'user';
        $statut = $data['statut'] ?? 'actif';

        return $this->usersRepo->updateUser($id, $nom, $prenom, $email, $role, $statut);
    }

    /**
     * Change le statut d'un utilisateur (bloque/débloque)
     */
    public function toggleStatus($id, $statut)
    {
        return $this->usersRepo->toggleUserStatus($id, $statut);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete($id)
    {
        return $this->usersRepo->deleteUser($id);
    }
}
?>
