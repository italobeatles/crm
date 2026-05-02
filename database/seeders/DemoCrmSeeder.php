<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Note;
use App\Models\Opportunity;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoCrmSeeder extends Seeder
{
    private Carbon $startDate;

    public function run(): void
    {
        $this->startDate = now()->subYears(2)->startOfDay();

        $this->resetTables();

        $users = $this->seedUsers();
        $leads = $this->seedLeads($users);
        $customers = $this->seedCustomers($users, $leads);
        $opportunities = $this->seedOpportunities($users, $customers, $leads);

        $this->seedContacts($customers);
        $this->seedActivities($users, $leads, $customers, $opportunities);
        $this->seedNotes($users, $leads, $customers, $opportunities);
        $this->seedSettings();
    }

    private function resetTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ([
            'tbnotes',
            'tbatividades',
            'tbcontacts',
            'tboportunidades',
            'tbleads',
            'tbcustomers',
            'tbsettings',
            'tbusers',
        ] as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function seedUsers(): Collection
    {
        $fixedUsers = collect([
            [
                'name' => 'Administrador CRM',
                'email' => 'admin@crm.local',
                'phone' => '(11) 99999-0001',
                'role' => User::ROLE_ADMIN,
                'status' => true,
                'password' => Hash::make('admin123'),
                'email_verificado_em' => now(),
                'criado_em' => $this->startDate->copy()->addDays(3),
                'atualizado_em' => now(),
            ],
            [
                'name' => 'Gestor Comercial',
                'email' => 'gestor@crm.local',
                'phone' => '(11) 99999-0002',
                'role' => User::ROLE_MANAGER,
                'status' => true,
                'password' => Hash::make('admin123'),
                'email_verificado_em' => now(),
                'criado_em' => $this->startDate->copy()->addDays(8),
                'atualizado_em' => now(),
            ],
            [
                'name' => 'Vendedor Demo',
                'email' => 'vendedor@crm.local',
                'phone' => '(11) 99999-0003',
                'role' => User::ROLE_SALES,
                'status' => true,
                'password' => Hash::make('admin123'),
                'email_verificado_em' => now(),
                'criado_em' => $this->startDate->copy()->addDays(12),
                'atualizado_em' => now(),
            ],
        ]);

        $teamUsers = collect([
            ['Camila Rocha', 'camila.rocha@crm.local', '(11) 99999-0011', User::ROLE_SALES],
            ['Rafael Mota', 'rafael.mota@crm.local', '(11) 99999-0012', User::ROLE_SALES],
            ['Bianca Faria', 'bianca.faria@crm.local', '(11) 99999-0013', User::ROLE_SALES],
            ['Leandro Pires', 'leandro.pires@crm.local', '(11) 99999-0014', User::ROLE_SUPPORT],
            ['Marina Azevedo', 'marina.azevedo@crm.local', '(11) 99999-0015', User::ROLE_SUPPORT],
        ])->map(function (array $user, int $index) {
            return [
                'name' => $user[0],
                'email' => $user[1],
                'phone' => $user[2],
                'role' => $user[3],
                'status' => true,
                'password' => Hash::make('admin123'),
                'email_verificado_em' => now(),
                'criado_em' => $this->startDate->copy()->addDays(15 + ($index * 4)),
                'atualizado_em' => now(),
            ];
        });

        return $fixedUsers
            ->merge($teamUsers)
            ->map(fn (array $data) => User::create($data));
    }

    private function seedLeads(Collection $users): Collection
    {
        $salesUsers = $users->filter(fn (User $user) => in_array($user->role, [User::ROLE_MANAGER, User::ROLE_SALES], true))->values();
        $origins = array_keys(Lead::originOptions());
        $statuses = ['novo', 'contato', 'qualificado', 'perdido'];
        $segments = ['Industria', 'Varejo', 'Tecnologia', 'Saude', 'Educacao', 'Logistica', 'Servicos'];
        $cities = ['Sao Paulo', 'Campinas', 'Santos', 'Sorocaba', 'Ribeirao Preto', 'Curitiba', 'Belo Horizonte'];

        $leads = collect();

        for ($i = 1; $i <= 260; $i++) {
            $responsavel = $salesUsers->random();
            $createdAt = $this->randomDate();
            $segment = fake('pt_BR')->randomElement($segments);
            $city = fake('pt_BR')->randomElement($cities);
            $companyName = "{$segment} {$city} {$i}";

            $leads->push(Lead::create([
                'nome' => $companyName,
                'email' => 'contato'.$i.'@empresa'.$i.'.com.br',
                'telefone' => fake('pt_BR')->cellphoneNumber(),
                'origem' => fake()->randomElement($origins),
                'status' => fake()->randomElement($statuses),
                'observacoes' => fake('pt_BR')->sentence(12),
                'id_usuario_responsavel' => $responsavel->id,
                'criado_em' => $createdAt,
                'atualizado_em' => $createdAt->copy()->addDays(rand(1, 35)),
            ]));
        }

        return $leads;
    }

    private function seedCustomers(Collection $users, Collection $leads): Collection
    {
        $owners = $users->filter(fn (User $user) => in_array($user->role, [User::ROLE_MANAGER, User::ROLE_SALES, User::ROLE_SUPPORT], true))->values();
        $customers = collect();

        foreach ($leads->random(150) as $lead) {
            $createdAt = $lead->criado_em->copy()->addDays(rand(5, 50));
            $customer = Customer::create([
                'tipo' => fake()->randomElement(['pj', 'pf']),
                'nome' => $lead->nome,
                'documento' => fake()->numerify('##.###.###/0001-##'),
                'email' => $lead->email,
                'telefone' => $lead->telefone,
                'status' => fake()->randomElement(['ativo', 'ativo', 'ativo', 'inativo']),
                'observacoes' => 'Conta originada de lead e mantida pela equipe comercial.',
                'id_usuario_responsavel' => $owners->random()->id,
                'criado_em' => $createdAt,
                'atualizado_em' => $createdAt->copy()->addDays(rand(2, 120)),
            ]);

            $lead->update([
                'status' => 'convertido',
                'id_cliente_convertido' => $customer->id,
                'convertido_em' => $createdAt,
                'atualizado_em' => $createdAt,
            ]);

            $customers->push($customer);
        }

        for ($i = 1; $i <= 55; $i++) {
            $createdAt = $this->randomDate();
            $isCompany = rand(1, 100) <= 75;
            $name = $isCompany
                ? fake('pt_BR')->company().' '.$i
                : fake('pt_BR')->name();

            $customers->push(Customer::create([
                'tipo' => $isCompany ? 'pj' : 'pf',
                'nome' => $name,
                'documento' => $isCompany
                    ? fake()->numerify('##.###.###/0001-##')
                    : fake()->numerify('###.###.###-##'),
                'email' => fake('pt_BR')->safeEmail(),
                'telefone' => fake('pt_BR')->cellphoneNumber(),
                'status' => fake()->randomElement(['ativo', 'ativo', 'ativo', 'inativo']),
                'observacoes' => fake('pt_BR')->sentence(10),
                'id_usuario_responsavel' => $owners->random()->id,
                'criado_em' => $createdAt,
                'atualizado_em' => $createdAt->copy()->addDays(rand(3, 180)),
            ]));
        }

        return $customers;
    }

    private function seedContacts(Collection $customers): void
    {
        foreach ($customers as $customer) {
            $contactCount = rand(1, 3);

            for ($i = 1; $i <= $contactCount; $i++) {
                $createdAt = $customer->criado_em->copy()->addDays(rand(0, 20));

                Contact::create([
                    'id_cliente' => $customer->id,
                    'nome' => fake('pt_BR')->name(),
                    'cargo' => fake()->randomElement(['Comprador', 'Diretor Comercial', 'Financeiro', 'Operacoes', 'Proprietario']),
                    'email' => fake('pt_BR')->safeEmail(),
                    'telefone' => fake('pt_BR')->cellphoneNumber(),
                    'principal' => $i === 1,
                    'criado_em' => $createdAt,
                    'atualizado_em' => $createdAt->copy()->addDays(rand(0, 60)),
                ]);
            }
        }
    }

    private function seedOpportunities(Collection $users, Collection $customers, Collection $leads): Collection
    {
        $owners = $users->filter(fn (User $user) => in_array($user->role, [User::ROLE_MANAGER, User::ROLE_SALES], true))->values();
        $openStages = ['prospeccao', 'qualificacao', 'proposta', 'negociacao'];
        $titles = ['Implantacao CRM', 'Plano de expansao comercial', 'Automacao de follow-up', 'Consultoria de vendas', 'Reestruturacao de pipeline'];
        $opportunities = collect();

        foreach ($customers as $customer) {
            $opportunityCount = rand(1, 3);

            for ($i = 1; $i <= $opportunityCount; $i++) {
                $createdAt = $customer->criado_em->copy()->addDays(rand(0, 90));
                $stage = fake()->randomElement([
                    'prospeccao', 'qualificacao', 'proposta', 'negociacao',
                    'fechado_ganho', 'fechado_perdido',
                ]);

                $status = $stage === 'fechado_ganho'
                    ? 'ganha'
                    : ($stage === 'fechado_perdido' ? 'perdida' : 'aberta');

                $opportunity = Opportunity::create([
                    'id_cliente' => $customer->id,
                    'id_lead' => fake()->boolean(60) ? optional($leads->firstWhere('id_cliente_convertido', $customer->id))->id : null,
                    'titulo' => fake()->randomElement($titles).' - '.$customer->nome,
                    'valor' => rand(3000, 95000),
                    'etapa' => $stage,
                    'probabilidade' => $status === 'aberta' ? rand(20, 90) : ($status === 'ganha' ? 100 : rand(0, 30)),
                    'data_prevista_fechamento' => $createdAt->copy()->addDays(rand(20, 120))->toDateString(),
                    'status' => $status,
                    'observacoes' => fake('pt_BR')->sentence(14),
                    'id_usuario_responsavel' => $owners->random()->id,
                    'ganho_em' => $status === 'ganha' ? $createdAt->copy()->addDays(rand(25, 140)) : null,
                    'perdido_em' => $status === 'perdida' ? $createdAt->copy()->addDays(rand(15, 110)) : null,
                    'criado_em' => $createdAt,
                    'atualizado_em' => $createdAt->copy()->addDays(rand(2, 120)),
                ]);

                $opportunities->push($opportunity);
            }
        }

        foreach (range(1, 22) as $i) {
            $customer = $customers->random();
            $createdAt = now()->subDays(rand(1, 120));
            $stage = fake()->randomElement($openStages);

            $opportunities->push(Opportunity::create([
                'id_cliente' => $customer->id,
                'id_lead' => null,
                'titulo' => 'Negociacao ativa '.$i.' - '.$customer->nome,
                'valor' => rand(8000, 120000),
                'etapa' => $stage,
                'probabilidade' => rand(30, 85),
                'data_prevista_fechamento' => now()->addDays(rand(7, 70))->toDateString(),
                'status' => 'aberta',
                'observacoes' => 'Oportunidade em andamento no funil atual.',
                'id_usuario_responsavel' => $owners->random()->id,
                'criado_em' => $createdAt,
                'atualizado_em' => now()->subDays(rand(0, 10)),
            ]));
        }

        return $opportunities;
    }

    private function seedActivities(Collection $users, Collection $leads, Collection $customers, Collection $opportunities): void
    {
        $owners = $users->filter(fn (User $user) => $user->status)->values();
        $types = array_keys(Activity::typeOptions());
        $relatedChoices = collect([
            ['lead', $leads],
            ['customer', $customers],
            ['opportunity', $opportunities],
        ]);

        foreach (range(1, 900) as $i) {
            [$relatedType, $collection] = $relatedChoices->random();
            $related = $collection->random();
            $createdAt = $this->randomDate();
            $dueDate = $createdAt->copy()->addDays(rand(1, 18))->setTime(rand(8, 18), fake()->randomElement([0, 15, 30, 45]));
            $status = fake()->randomElement(['pendente', 'concluida', 'concluida', 'concluida', 'cancelada']);

            Activity::create([
                'relacionado_tipo' => $relatedType,
                'relacionado_id' => $related->id,
                'tipo' => fake()->randomElement($types),
                'titulo' => fake()->randomElement([
                    'Contato inicial',
                    'Follow-up comercial',
                    'Reuniao de apresentacao',
                    'Envio de proposta',
                    'Retorno financeiro',
                    'Agendar demonstracao',
                ]),
                'descricao' => fake('pt_BR')->sentence(16),
                'data_vencimento' => $dueDate,
                'concluido_em' => $status === 'concluida' ? $dueDate->copy()->subHours(rand(0, 10)) : null,
                'status' => $status,
                'id_usuario_responsavel' => $owners->random()->id,
                'criado_em' => $createdAt,
                'atualizado_em' => $status === 'concluida' ? $dueDate : $createdAt->copy()->addDays(rand(1, 12)),
            ]);
        }

        foreach (range(1, 35) as $i) {
            $opportunity = $opportunities->random();
            $dueDate = now()->subDays(rand(1, 20))->setTime(rand(8, 18), 0);

            Activity::create([
                'relacionado_tipo' => 'opportunity',
                'relacionado_id' => $opportunity->id,
                'tipo' => fake()->randomElement(['tarefa', 'ligacao', 'email']),
                'titulo' => 'Pendencia comercial '.$i,
                'descricao' => 'Atividade vencida para destacar o painel de pendencias.',
                'data_vencimento' => $dueDate,
                'concluido_em' => null,
                'status' => 'pendente',
                'id_usuario_responsavel' => $owners->random()->id,
                'criado_em' => $dueDate->copy()->subDays(rand(1, 8)),
                'atualizado_em' => $dueDate->copy()->subHours(rand(1, 12)),
            ]);
        }
    }

    private function seedNotes(Collection $users, Collection $leads, Collection $customers, Collection $opportunities): void
    {
        $authors = $users->filter(fn (User $user) => $user->status)->values();
        $targets = collect([
            ['lead', $leads->random(120)],
            ['customer', $customers->random(120)],
            ['opportunity', $opportunities->random(180)],
        ]);

        foreach ($targets as [$type, $collection]) {
            foreach ($collection as $item) {
                $noteCount = rand(1, 3);

                for ($i = 0; $i < $noteCount; $i++) {
                    $createdAt = $this->randomDate();

                    Note::create([
                        'relacionado_tipo' => $type,
                        'relacionado_id' => $item->id,
                        'conteudo' => fake('pt_BR')->paragraph(rand(1, 2)),
                        'id_usuario' => $authors->random()->id,
                        'criado_em' => $createdAt,
                        'atualizado_em' => $createdAt->copy()->addDays(rand(0, 10)),
                    ]);
                }
            }
        }
    }

    private function seedSettings(): void
    {
        $settings = [
            ['crm.company_name', 'CRM Simples', 'Nome da empresa apresentado no topo do sistema.'],
            ['crm.currency', 'BRL', 'Moeda padrao para propostas e oportunidades.'],
            ['crm.default_pipeline_stage', 'prospeccao', 'Etapa inicial sugerida para novas oportunidades.'],
            ['crm.dashboard_period_days', '30', 'Periodo padrao de analise no dashboard.'],
            ['crm.reminder_days', '3', 'Quantidade de dias para alerta de atividades proximas.'],
        ];

        foreach ($settings as [$chave, $valor, $descricao]) {
            Setting::create([
                'chave' => $chave,
                'valor' => $valor,
                'descricao' => $descricao,
                'criado_em' => $this->startDate->copy()->addDays(rand(1, 20)),
                'atualizado_em' => now(),
            ]);
        }
    }

    private function randomDate(): Carbon
    {
        return Carbon::createFromTimestamp(rand($this->startDate->timestamp, now()->subDays(3)->timestamp));
    }
}
