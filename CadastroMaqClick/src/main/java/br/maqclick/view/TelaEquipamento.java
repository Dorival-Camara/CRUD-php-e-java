package br.maqclick.view;

import br.maqclick.dao.EquipamentoDAO;
import br.maqclick.model.Equipamento;
import java.util.List;
import javax.swing.JOptionPane;
import javax.swing.table.DefaultTableModel;

public class TelaEquipamento extends javax.swing.JFrame {

    private int idSelecionado = 0;

    private javax.swing.JTextField txtNome;
    private javax.swing.JTextField txtDescricao;
    private javax.swing.JTextField txtStatus;
    private javax.swing.JTextField txtCategoria;
    private javax.swing.JButton btnCadastrar;
    private javax.swing.JButton btnAlterar;
    private javax.swing.JButton btnExcluir;
    private javax.swing.JButton btnLimpar;
    private javax.swing.JTable tabelaEquipamentos;

    public TelaEquipamento() {
        initComponents();
        carregarTabela();
    }

    private void initComponents() {
        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("MaqClick - Equipamentos");
        setSize(760, 500);
        setResizable(false);
        setLocationRelativeTo(null);
        setLayout(null); // posições definidas com setBounds(x, y, largura, altura)

        // Rótulos e campos
        javax.swing.JLabel lblNome = new javax.swing.JLabel("Nome:");
        lblNome.setBounds(20, 20, 110, 25);
        add(lblNome);
        txtNome = new javax.swing.JTextField();
        txtNome.setBounds(140, 20, 580, 25);
        add(txtNome);

        javax.swing.JLabel lblDescricao = new javax.swing.JLabel("Descrição:");
        lblDescricao.setBounds(20, 55, 110, 25);
        add(lblDescricao);
        txtDescricao = new javax.swing.JTextField();
        txtDescricao.setBounds(140, 55, 580, 25);
        add(txtDescricao);

        javax.swing.JLabel lblStatus = new javax.swing.JLabel("Status:");
        lblStatus.setBounds(20, 90, 110, 25);
        add(lblStatus);
        txtStatus = new javax.swing.JTextField();
        txtStatus.setBounds(140, 90, 580, 25);
        add(txtStatus);

        javax.swing.JLabel lblCategoria = new javax.swing.JLabel("ID da categoria:");
        lblCategoria.setBounds(20, 125, 110, 25);
        add(lblCategoria);
        txtCategoria = new javax.swing.JTextField();
        txtCategoria.setBounds(140, 125, 580, 25);
        add(txtCategoria);

        // Botões
        btnCadastrar = new javax.swing.JButton("Cadastrar");
        btnCadastrar.setBounds(20, 165, 120, 30);
        add(btnCadastrar);

        btnAlterar = new javax.swing.JButton("Alterar");
        btnAlterar.setBounds(150, 165, 120, 30);
        add(btnAlterar);

        btnExcluir = new javax.swing.JButton("Excluir");
        btnExcluir.setBounds(280, 165, 120, 30);
        add(btnExcluir);

        btnLimpar = new javax.swing.JButton("Limpar");
        btnLimpar.setBounds(410, 165, 120, 30);
        add(btnLimpar);

        // Tabela
        tabelaEquipamentos = new javax.swing.JTable();
        tabelaEquipamentos.setModel(new DefaultTableModel(
            new Object[][] {},
            new String[] {"ID", "Nome", "Descrição", "Status", "Categoria"}
        ));
        javax.swing.JScrollPane barraRolagem = new javax.swing.JScrollPane(tabelaEquipamentos);
        barraRolagem.setBounds(20, 210, 700, 230);
        add(barraRolagem);

        // Eventos
        btnCadastrar.addActionListener(evento -> btnCadastrarActionPerformed());
        btnAlterar.addActionListener(evento -> btnAlterarActionPerformed());
        btnExcluir.addActionListener(evento -> btnExcluirActionPerformed());
        btnLimpar.addActionListener(evento -> limparCampos());
        tabelaEquipamentos.getSelectionModel().addListSelectionListener(evento -> {
            if (!evento.getValueIsAdjusting()) {
                tabelaEquipamentosSelecionada();
            }
        });
    }

    // Lê o ID da categoria; devolve 0 se estiver vazio ou não for número
    private int lerIdCategoria() {
        try {
            return Integer.parseInt(txtCategoria.getText().trim());
        } catch (NumberFormatException erro) {
            JOptionPane.showMessageDialog(this, "Informe o ID da categoria (número).");
            txtCategoria.requestFocus();
            return 0;
        }
    }

    private void btnCadastrarActionPerformed() {
        if (txtNome.getText().trim().isEmpty()) {
            JOptionPane.showMessageDialog(this, "Informe o nome do equipamento.");
            txtNome.requestFocus();
            return;
        }
        int idCategoria = lerIdCategoria();
        if (idCategoria == 0) {
            return;
        }
        String nome = txtNome.getText();
        String descricao = txtDescricao.getText();
        String status = txtStatus.getText();
        Equipamento equipamento = new Equipamento(nome, descricao, status, idCategoria);
        EquipamentoDAO dao = new EquipamentoDAO();
        dao.cadastrar(equipamento);
        carregarTabela();
        limparCampos();
    }

    private void limparCampos() {
        txtNome.setText("");
        txtDescricao.setText("");
        txtStatus.setText("");
        txtCategoria.setText("");
        txtNome.requestFocus();
        idSelecionado = 0;
    }

    private void carregarTabela() {
        DefaultTableModel modelo = (DefaultTableModel) tabelaEquipamentos.getModel();
        modelo.setRowCount(0);
        EquipamentoDAO dao = new EquipamentoDAO();
        List<Equipamento> lista = dao.listar();
        for (Equipamento equipamento : lista) {
            modelo.addRow(new Object[] {
                equipamento.getId(),
                equipamento.getNome(),
                equipamento.getDescricao(),
                equipamento.getStatus(),
                equipamento.getIdCategoria()
            });
        }
    }

    private void tabelaEquipamentosSelecionada() {
        int linha = tabelaEquipamentos.getSelectedRow();
        if (linha >= 0) {
            idSelecionado = Integer.parseInt(
                tabelaEquipamentos.getValueAt(linha, 0).toString()
            );
            txtNome.setText(tabelaEquipamentos.getValueAt(linha, 1).toString());
            txtDescricao.setText(tabelaEquipamentos.getValueAt(linha, 2).toString());
            txtStatus.setText(tabelaEquipamentos.getValueAt(linha, 3).toString());
            txtCategoria.setText(tabelaEquipamentos.getValueAt(linha, 4).toString());
        }
    }

    private void btnAlterarActionPerformed() {
        if (idSelecionado == 0) {
            JOptionPane.showMessageDialog(this, "Selecione um equipamento.");
            return;
        }
        int idCategoria = lerIdCategoria();
        if (idCategoria == 0) {
            return;
        }
        Equipamento equipamento = new Equipamento(
            idSelecionado,
            txtNome.getText(),
            txtDescricao.getText(),
            txtStatus.getText(),
            idCategoria
        );
        EquipamentoDAO dao = new EquipamentoDAO();
        dao.alterar(equipamento);
        carregarTabela();
        limparCampos();
        idSelecionado = 0;
    }

    private void btnExcluirActionPerformed() {
        if (idSelecionado == 0) {
            JOptionPane.showMessageDialog(this, "Selecione um equipamento.");
            return;
        }
        int resposta = JOptionPane.showConfirmDialog(
            this,
            "Deseja realmente excluir este equipamento?",
            "Confirmação",
            JOptionPane.YES_NO_OPTION
        );
        if (resposta == JOptionPane.YES_OPTION) {
            EquipamentoDAO dao = new EquipamentoDAO();
            dao.excluir(idSelecionado);
            carregarTabela();
            limparCampos();
            idSelecionado = 0;
        }
    }

    public static void main(String[] argumentos) {
        javax.swing.SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                new TelaEquipamento().setVisible(true);
            }
        });
    }
}