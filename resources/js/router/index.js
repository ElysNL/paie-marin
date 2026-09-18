import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import AppLayout from '@/components/AppLayout.vue';
import Login from '@/components/Login.vue';
import Dashboard from '@/components/Dashboard.vue';
import ArmateurList from '@/components/Armateurs/List.vue';
import ArmateurForm from '@/components/Armateurs/Form.vue';
import NavireList from '@/components/Navires/List.vue';
import NavireForm from '@/components/Navires/Form.vue';
import EmployeList from '@/components/Employes/List.vue';
import EmployeForm from '@/components/Employes/Form.vue';
import AffectationList from '@/components/Affectations/List.vue';
import AffectationForm from '@/components/Affectations/Form.vue';
import ContratArmateurList from '@/components/ContratsArmateur/List.vue';
import ContratArmateurForm from '@/components/ContratsArmateur/Form.vue';
import PaysList from '@/components/Pays/List.vue';
import PaysForm from '@/components/Pays/Form.vue';
import DeviseList from '@/components/Devises/List.vue';
import DeviseForm from '@/components/Devises/Form.vue';
import FonctionList from '@/components/Fonctions/List.vue';
import FonctionForm from '@/components/Fonctions/Form.vue';
import ClassificationList from '@/components/Classifications/List.vue';
import ClassificationForm from '@/components/Classifications/Form.vue';
import CompagnieList from '@/components/Compagnies/List.vue';
import CompagnieForm from '@/components/Compagnies/Form.vue';
import PaieList from '@/components/Paies/List.vue';
import PaieForm from '@/components/Paies/Form.vue';
import PaieDetail from '@/components/Paies/Detail.vue';
import BulletinList from '@/components/Bulletins/List.vue';
import BulletinDetail from '@/components/Bulletins/Detail.vue';
import AvanceList from '@/components/Avances/List.vue';
import AvanceForm from '@/components/Avances/Form.vue';
import DelegationList from '@/components/Delegations/List.vue';
import DelegationForm from '@/components/Delegations/Form.vue';
import BanqueList from '@/components/Banques/List.vue';
import BanqueForm from '@/components/Banques/Form.vue';
import UserList from '@/components/Users/List.vue';
import UserForm from '@/components/Users/Form.vue';
import Forbidden from '@/components/Forbidden.vue';

const routes = [
  { path: '/login', name: 'login', component: Login, meta: { public: true, title: 'Connexion' } },

  {
    path: '/',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', redirect: '/dashboard' },
      { path: 'dashboard', component: Dashboard, meta: { title: 'Tableau de bord' } },

      { path: 'armateurs', component: ArmateurList, meta: { title: 'Armateurs' } },
      { path: 'armateurs/create', component: ArmateurForm, meta: { title: 'Nouvel armateur' } },
      { path: 'armateurs/:id/edit', component: ArmateurForm, props: true, meta: { title: "Modifier l'armateur" } },

      { path: 'navires', component: NavireList, meta: { title: 'Navires' } },
      { path: 'navires/create', component: NavireForm, meta: { title: 'Nouveau navire' } },
      { path: 'navires/:id/edit', component: NavireForm, props: true, meta: { title: 'Modifier le navire' } },

      { path: 'employes', component: EmployeList, meta: { title: 'Employés' } },
      { path: 'employes/create', component: EmployeForm, meta: { title: 'Nouvel employé' } },
      { path: 'employes/:id/edit', component: EmployeForm, props: true, meta: { title: "Modifier l'employé" } },

      { path: 'affectations', component: AffectationList, meta: { title: 'Affectations' } },
      { path: 'affectations/create', component: AffectationForm, meta: { title: "Nouvelle affectation" } },
      { path: 'affectations/:id/edit', component: AffectationForm, props: true, meta: { title: "Modifier l'affectation" } },

      { path: 'contrats-armateur', component: ContratArmateurList, meta: { title: "Contrats d'armateur" } },
      { path: 'contrats-armateur/create', component: ContratArmateurForm, meta: { title: "Nouveau contrat d'armateur" } },
      { path: 'contrats-armateur/:id/edit', component: ContratArmateurForm, props: true, meta: { title: "Modifier le contrat d'armateur" } },

      { path: 'pays', component: PaysList, meta: { title: 'Pays' } },
      { path: 'pays/create', component: PaysForm, meta: { title: 'Nouveau pays' } },
      { path: 'pays/:id/edit', component: PaysForm, props: true, meta: { title: 'Modifier le pays' } },

      { path: 'devises', component: DeviseList, meta: { title: 'Devises' } },
      { path: 'devises/create', component: DeviseForm, meta: { title: 'Nouvelle devise' } },
      { path: 'devises/:id/edit', component: DeviseForm, props: true, meta: { title: 'Modifier la devise' } },

      { path: 'fonctions', component: FonctionList, meta: { title: 'Fonctions' } },
      { path: 'fonctions/create', component: FonctionForm, meta: { title: 'Nouvelle fonction' } },
      { path: 'fonctions/:id/edit', component: FonctionForm, props: true, meta: { title: 'Modifier la fonction' } },

      { path: 'classifications', component: ClassificationList, meta: { title: 'Classifications' } },
      { path: 'classifications/create', component: ClassificationForm, meta: { title: 'Nouvelle classification' } },
      { path: 'classifications/:id/edit', component: ClassificationForm, props: true, meta: { title: 'Modifier la classification' } },

      { path: 'compagnies', component: CompagnieList, meta: { title: 'Compagnies' } },
      { path: 'compagnies/create', component: CompagnieForm, meta: { title: 'Nouvelle compagnie' } },
      { path: 'compagnies/:id/edit', component: CompagnieForm, props: true, meta: { title: 'Modifier la compagnie' } },

      { path: 'paies', component: PaieList, meta: { title: 'Paies' } },
      { path: 'paies/create', component: PaieForm, meta: { title: 'Nouvelle paie' } },
      { path: 'paies/:id', component: PaieDetail, props: true, meta: { title: 'Détail de la paie' } },
      { path: 'paies/:id/edit', component: PaieForm, props: true, meta: { title: 'Modifier la paie' } },

      { path: 'bulletins', component: BulletinList, meta: { title: 'Bulletins' } },
      { path: 'bulletins/:id', component: BulletinDetail, props: true, meta: { title: 'Détail du bulletin' } },

      { path: 'avances', component: AvanceList, meta: { title: 'Avances' } },
      { path: 'avances/create', component: AvanceForm, meta: { title: 'Nouvelle avance' } },
      { path: 'avances/:id/edit', component: AvanceForm, props: true, meta: { title: 'Modifier l\u0027avance' } },

      { path: 'delegations', component: DelegationList, meta: { title: 'Délégations' } },
      { path: 'delegations/create', component: DelegationForm, meta: { title: 'Nouvelle délégation' } },
      { path: 'delegations/:id/edit', component: DelegationForm, props: true, meta: { title: 'Modifier la délégation' } },

      { path: 'banques', component: BanqueList, meta: { title: 'Banques' } },
      { path: 'banques/create', component: BanqueForm, meta: { title: 'Nouvelle banque' } },
      { path: 'banques/:id/edit', component: BanqueForm, props: true, meta: { title: 'Modifier la banque' } },

      { path: 'utilisateurs', component: UserList, meta: { title: 'Utilisateurs', roles: ['admin'] } },
      { path: 'utilisateurs/create', component: UserForm, meta: { title: 'Nouvel utilisateur', roles: ['admin'] } },
      { path: 'utilisateurs/:id/edit', component: UserForm, props: true, meta: { title: "Modifier l'utilisateur", roles: ['admin'] } },

      { path: '403', component: Forbidden, meta: { title: 'Accès interdit', public: true } },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to) => {
  document.title = (to.meta?.title ? `${to.meta.title} · ` : '') + 'Gestion de Paie';

  const auth = useAuthStore();

  if (!auth.ready) {
    await auth.init();
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { path: '/login', query: { redirect: to.fullPath } };
  }

  if (to.meta.public && auth.isAuthenticated) {
    return { path: '/dashboard' };
  }

  if (to.meta.roles && to.meta.roles.length > 0) {
    if (!auth.hasRole(...to.meta.roles)) {
      return { path: '/403' };
    }
  }
});

export default router;