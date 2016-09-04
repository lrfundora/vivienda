<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Cargo,
    AppBundle\Entity\Usuario,
    AppBundle\Entity\Categoria,
    AppBundle\Entity\Contrato,
    AppBundle\Entity\Entidad,
    AppBundle\Entity\EscalaSalarial,
    AppBundle\Entity\Escolaridad,
    AppBundle\Entity\Integracion,
    AppBundle\Entity\Role;
use AppBundle\Entity\Trabajador;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class loadDefaultDataBase extends AbstractFixture
{
    private $encode;

    public function __construct()
    {
        $this->encode = new BCryptPasswordEncoder(15);
    }

    public function load(ObjectManager $manager)
    {
        // Roles de la aplicación
        $roles = [
            'admin' => ['Administrador', 'ROLE_ADMIN'],
            'director' => ['Director', 'ROLE_DIRECTOR'],
            'jefe_abogado' => ['Jefe de Abogados', 'ROLE_JABOGADO'],
            'abogado' => ['Abogado', 'ROLE_ABOGADO'],
            'jefe_RH' => ['Jefe de Recursos Humanos', 'ROLE_JRH'],
            'RH' => ['Trabajador de Recursos Humanos', 'ROLE_RH'],
            'CF' => ['Responsable de Control de Fondo', 'ROLE_CF'],
            'recepcion' => ['Recepción', 'ROLE_RECEPCION']
        ];

        foreach ($roles as $id => $data) {
            $tmp = new Role();
            $tmp->setNombre($data[0])
                ->setRole($data[1]);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Usuario Administrador
        $adminUser = new Usuario();
        $adminUser
            ->setUser('admin')
            ->setPassword('Admin.2016')
            ->addRole($this->getReference('admin'));
        $this->encodePassword($adminUser);
        $manager->persist($adminUser);

        // Cargos
        $cargo = [
            'c1' => 'Director',
            'c2' => 'Subdirector',
            'c3' => 'Secretaria',
            'c4' => 'Especialista B para la Defensa y Defensa Civil',
            'c5' => 'Técnico en Atención a la población',
            'c6' => 'Asesor C Jurídico',
            'c7' => 'Técnico en ahorro y uso racional de la energía',
            'c8' => 'Chofer D',
            'c9' => 'Técnico en Ciencias Informática',
            'c10' => 'Jefe de Departamento Técnico',
            'c11' => 'Jefe de Departamento de Control de Fondo',
            'c12' => 'Jefe de Departamento Legal',
            'c13' => 'Jefe de Departamento Económmico',
            'c14' => 'Especialista B en Inversión de la Vivienda y Urbanismo',
            'c15' => 'Especialista B en Control de Inmobiliario y renta',
            'c16' => 'Técnico en Control de Inmobiliario y renta',
            'c17' => 'Técnico en Conservación y Rehabilitación',
            'c18' => 'Abogado',
            'c19' => 'Técnico en Administración de la Vivienda, Edificio multifamiliar y común',
            'c20' => 'Controlador de Archivos y Expedientes',
            'c21' => 'Técnico Investigador de la Vivienda',
            'c22' => 'Tramitador de Documentos',
            'c23' => 'Jefe de Oficinas de Trámites',
            'c24' => 'Recepcionista',
            'c25' => 'Auxiliar General de Servicios',
            'c26' => 'Especialista C en Gestión',
            'c27' => 'Técnico A en Gestión Económica',
            'c28' => 'Especialista C en Gestión de Recursos Humanos',
            'c29' => 'Serenos',
            'c30' => 'Especialista B en derechos sobre la vivienda'
        ];

        foreach ($cargo as $id => $data) {
            $tmp = new Cargo();
            $tmp->setNombre($data);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Categorías Ocupacionales
        $categoria = [
            'ca1' => 'Administrativo',
            'ca2' => 'Cuadro',
            'ca3' => 'Obrero',
            'ca4' => 'Servicio',
            'ca5' => 'Técnico'
        ];

        foreach ($categoria as $id => $data) {
            $tmp = new Categoria();
            $tmp->setNombre($data);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Tipos de Contratos
        $contrato = [
            'co1' => 'Determinado',
            'co2' => 'Indeterminado'
        ];

        foreach ($contrato as $id => $data) {
            $tmp = new Contrato();
            $tmp->setTipo($data);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Escala Salarial
        $escalaSalarial = [
            'es1' => ['II', '255.00'],
            'es2' => ['IV', '280.00'],
            'es3' => ['IV', '334.74'],
            'es4' => ['V', '275.00'],
            'es5' => ['VIII', '345.00'],
            'es6' => ['X', '385.00'],
            'es7' => ['XI', '425.00'],
            'es8' => ['XIII', '400.00'],
            'es9' => ['XIV', '425.00'],
            'es10' => ['XV', '440.00']
        ];

        foreach ($escalaSalarial as $id => $data) {
            $tmp = new EscalaSalarial();
            $tmp
                ->setEscala($data[0])
                ->setSalario($data[1]);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Nivel Escolar
        $escolaridad = [
            'e1' => '9no',
            'e2' => '12mo',
            'e3' => 'Técnico Medio',
            'e4' => 'Universitario'
        ];

        foreach ($escolaridad as $id => $data) {
            $tmp = new Escolaridad();
            $tmp->setNivel($data);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Integración
        $integracion = [
            'i1' => 'BPD',
            'i2' => 'CDR',
            'i3' => 'CTC',
            'i4' => 'FMC',
            'i5' => 'PCC',
            'i6' => 'UJC',
            'i7' => 'UM',
            'i8' => 'N.I.D'
        ];

        foreach ($integracion as $id => $data) {
            $tmp = new Integracion();
            $tmp->setNombre($data);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        // Entidades
        $entidades = [
            'en1' => ['Vivienda Varadero', '1ra ave % calle 20 y 21', '662115', false],
            'en2' => ['Vivienda Santa Marta', '1ra ave % calle 5 y 6', '615618', false],
            'en3' => ['Vivienda Cardena', 'Cardenas', '520011', false],
            'en4' => ['Vivienda Direccion Municipal', 'Cardenas', '526691', false],
            'en5' => ['Gobierno Cardenas', 'Cardenas', '525511', true]
        ];

        foreach ($entidades as $id => $data) {
            $tmp = new Entidad();
            $tmp
                ->setNombre($data[0])
                ->setDireccion($data[1])
                ->setTelefono($data[2])
                ->setExterna($data[3]);
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        //Trabajadores
        $trabajadores = [
            't1' => ['74061009475', 'Yuselys', 'Caballero Guedes', 0, 'Calle 22 # 403 e/ Progreso y Phinney', 'c1', 'ca2', 'e4', 'es8', 'co2', 'en3', ['i2', 'i3', 'i4']],
            't2' => ['55032100174', 'Ana Maria', 'Ajete Terraz', 0, 'Laborde # 453 e/ industria y  obispo', 'c24', 'ca4', 'e1', 'es1', 'co2', 'en3', ['i2', 'i4']],
            't3' => ['74700842539', 'Marvelis', 'Masso Neyra', 0, 'Cristina # 210 e/ Linea y Souberville', 'c11', 'ca2', 'e4', 'es8', 'co2', 'en3', ['i2', 'i4', 'i5']],
            't4' => ['60101713079', 'María Margarita', 'González Pérez', 0, 'Ave 67 # 601 e/ calzada y cristina', 'c28', 'ca5', 'e3', 'es7', 'co2', 'en3', ['i1', 'i2', 'i3', 'i4']],
            't5' => ['86010209939', 'Kirenia', 'Baragaño Vasallo', 0, 'Calle 13 A e/ 24 y 25', 'c30', 'ca5', 'e4', 'es7', 'co2', 'en3', ['i3', 'i4']],
            't6' => ['72123100620', 'Arisbel', 'López Veliz', 0, 'Coronel Verdugo #63 e/Laborde y jenez', 'c30', 'ca5', 'e4', 'es7', 'co2', 'en3', ['i1', 'i2', 'i3']],
            't7' => ['65090400489', 'Manuel', 'Muñoz Oliva', 1, 'Calle 22 # 403 e/ Progreso y Phinney', 'c30', 'ca5', 'e4', 'es7', 'co2', 'en3', ['i1', 'i2', 'i3']],
            't8' => ['76071624479', 'Maryoret', 'Guardiola Pérez', 0, 'Calle 22 # 403 e/ Progreso y Phinney', 'c30', 'ca5', 'e4', 'es7', 'co2', 'en3', ['i1', 'i3', 'i4', 'i5']]
        ];

        foreach ($trabajadores as $id => $data) {
            $tmp = new Trabajador();
            $tmp
                ->setCarnet($data[0])
                ->setNombre($data[1])
                ->setApellidos($data[2])
                ->setSexo($data[3])
                ->setDireccion($data[4])
                ->setCargo($this->getReference($data[5]))
                ->setCategoria($this->getReference($data[6]))
                ->setEscolaridad($this->getReference($data[7]))
                ->setEscalaSalarial($this->getReference($data[8]))
                ->setContrato($this->getReference($data[9]))
                ->setEntidad($this->getReference($data[10]));
            foreach ($data[11] as $int) {
                $tmp
                    ->addIntegracion($this->getReference($int));
            }
            $this->addReference($id, $tmp);
            $manager->persist($tmp);
        }

        //Usuarios
        $usuarios = [
            'u1' => ['yuselys.caballero', '12345', 'director', 't1'],
            'u2' => ['ana.ajete', '12345', 'recepcion', 't2'],
            'u3' => ['marvelis.masso', '12345', 'CF', 't3'],
            'u4' => ['maria.gonzalez', '12345', 'jefe_RH', 't4'],
            'u5' => ['kirenia.baragano', '12345', 'abogado', 't5'],
            'u6' => ['arisbel.lopez', '12345', 'abogado', 't6'],
            'u7' => ['manuel.munoz', '12345', 'abogado', 't7'],
            'u8' => ['maryoret.guardiola', '12345', 'jefe_abogado', 't8']

        ];
        foreach ($usuarios as $id => $data) {
            $tmp = new Usuario();
            $tmp
                ->setUser($data[0])
                ->setPassword($data[1])
                ->addRole($this->getReference($data[2]))
                ->setTrabajador($this->getReference($data[3]));
            $this->encodePassword($tmp);
            $manager->persist($tmp);
        }

        // Guardando datos
        $manager->flush();

    }

    private function encodePassword($entity)
    {
        $entity->setSalt(md5(uniqid(rand(), true)));
        $password = $this->encode->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
    }
}